<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleDataDownloadEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\ClearSurveySampleModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SampleFilterModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SampleUploadStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SurveyCreateSampleColumnModel;
use Nikoleesg\NfieldAdmin\Resources\SurveySampleResource;
use Nikoleesg\NfieldAdmin\Support\CsvParser;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;
use RuntimeException;

class SurveySampleService implements SurveyScopedInterface
{
    use ScopedToSurvey;

    public function __construct(
        protected SurveySampleCollectionEndpointInterface $surveySampleCollectionEndpoint,
        protected SurveySampleEndpointInterface $surveySampleEndpoint,
        protected SurveySampleDataDownloadEndpointInterface $surveySampleDataDownloadEndpoint,
    ) {}

    /**
     * Download and parse sample data from the survey.
     *
     * Sample columns are defined per survey, so a record has no fixed shape and
     * is returned as a keyed array of its CSV header columns.
     *
     * @return Collection<int, array<string, string>>
     *
     * @throws RuntimeException If CSV parsing fails
     */
    public function downloadSampleData(): Collection
    {
        $rawCsvData = $this->surveySampleCollectionEndpoint->download($this->getSurveyId());

        return collect(CsvParser::parse($rawCsvData));
    }

    public function uploadSampleData(string $sampleData, ?string $fileName = null): SampleUploadStatus
    {
        $fileName = $fileName ?? $this->generateSampleFileName();
        $response = $this->surveySampleCollectionEndpoint->upload($this->getSurveyId(), $sampleData, $fileName);

        return SampleUploadStatus::from($response);
    }

    /**
     * @param  iterable<int, array|SampleFilterModel>  $filters
     */
    public function blockSampleData(iterable $filters): BackgroundActivityStatus
    {
        return BackgroundActivityStatus::from(
            $this->surveySampleCollectionEndpoint->block($this->getSurveyId(), $this->normaliseFilters($filters))
        );
    }

    /**
     * Create a survey sample (Online).
     *
     * @param  iterable<int, array|SurveyCreateSampleColumnModel>  $columns
     * @return Collection<int, SurveyCreateSampleColumnModel>
     */
    public function createSampleData(iterable $columns): Collection
    {
        $payload = [];

        foreach ($columns as $column) {
            $payload[] = SurveyCreateSampleColumnModel::from($column)->toArray();
        }

        return SurveyCreateSampleColumnModel::collect(
            $this->surveySampleCollectionEndpoint->create($this->getSurveyId(), $payload),
            Collection::class
        );
    }

    /**
     * @param  iterable<int, array|SampleFilterModel>  $filters
     */
    public function resetSampleData(iterable $filters): BackgroundActivityStatus
    {
        return BackgroundActivityStatus::from(
            $this->surveySampleCollectionEndpoint->reset($this->getSurveyId(), $this->normaliseFilters($filters))
        );
    }

    public function clearSampleDataColumns(array|ClearSurveySampleModel $data): BackgroundActivityStatus
    {
        $payload = ClearSurveySampleModel::from($data)->toArray();

        return BackgroundActivityStatus::from(
            $this->surveySampleCollectionEndpoint->clear($this->getSurveyId(), $payload)
        );
    }

    public function requestSampleDownload(?string $fileName = null): BackgroundActivityStatus
    {
        $fileName = $fileName ?? $this->generateSampleFileName();

        return BackgroundActivityStatus::from(
            $this->surveySampleDataDownloadEndpoint->requestDownload($this->getSurveyId(), $fileName)
        );
    }

    /**
     * Return SurveySampleResource for the specified survey
     */
    public function forInterview(int $interviewId): SurveySampleResource
    {
        $surveySampleResource = new SurveySampleResource($this->surveySampleEndpoint, $this->surveySampleCollectionEndpoint);

        return $surveySampleResource
            ->setSurveyId($this->getSurveyId())
            ->setInterviewId($interviewId);
    }

    /**
     * The sample filter endpoints take a bare JSON array of filter clauses.
     *
     * @param  iterable<int, array|SampleFilterModel>  $filters
     * @return array<int, array<string, mixed>>
     */
    protected function normaliseFilters(iterable $filters): array
    {
        $payload = [];

        foreach ($filters as $filter) {
            $payload[] = SampleFilterModel::from($filter)->toArray();
        }

        return $payload;
    }

    /**
     * Generate a default filename for sample download
     *
     * @return string Generated filename with survey ID and timestamp
     */
    protected function generateSampleFileName(): string
    {
        return sprintf(
            'Survey_%s_Samples_%s',
            $this->getSurveyId(),
            now()->format('Ymd_His')
        );
    }
}
