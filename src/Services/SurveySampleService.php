<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleDataDownloadEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\ClearSurveySampleModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SampleFilterModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SampleUploadStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SurveyCreateSampleColumnModel;
use Nikoleesg\NfieldAdmin\Resources\SurveySampleResource;
use Nikoleesg\NfieldAdmin\Support\CsvParser;
use RuntimeException;

class SurveySampleService
{
    public function __construct(
        protected SurveySampleCollectionEndpointInterface $surveySampleCollectionEndpoint,
        protected SurveySampleEndpointInterface $surveySampleEndpoint,
        protected SurveySampleDataDownloadEndpointInterface $surveySampleDataDownloadEndpoint,
        protected readonly string $surveyId,
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
        $rawCsvData = $this->surveySampleCollectionEndpoint->download($this->surveyId);

        return collect(CsvParser::parse($rawCsvData));
    }

    public function uploadSampleData(string $sampleData, ?string $fileName = null): SampleUploadStatus
    {
        $fileName = $fileName ?? $this->generateSampleFileName();
        $response = $this->surveySampleCollectionEndpoint->upload($this->surveyId, $sampleData, $fileName);

        return SampleUploadStatus::from($response);
    }

    /**
     * @param  iterable<int, array|SampleFilterModel>  $filters
     */
    public function blockSampleData(iterable $filters): BackgroundActivityStatus
    {
        return BackgroundActivityStatus::from(
            $this->surveySampleCollectionEndpoint->block($this->surveyId, $this->normaliseFilters($filters))
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
            $this->surveySampleCollectionEndpoint->create($this->surveyId, $payload),
            Collection::class
        );
    }

    /**
     * @param  iterable<int, array|SampleFilterModel>  $filters
     */
    public function resetSampleData(iterable $filters): BackgroundActivityStatus
    {
        return BackgroundActivityStatus::from(
            $this->surveySampleCollectionEndpoint->reset($this->surveyId, $this->normaliseFilters($filters))
        );
    }

    public function clearSampleDataColumns(array|ClearSurveySampleModel $data): BackgroundActivityStatus
    {
        $payload = ClearSurveySampleModel::from($data)->toArray();

        return BackgroundActivityStatus::from(
            $this->surveySampleCollectionEndpoint->clear($this->surveyId, $payload)
        );
    }

    public function requestSampleDownload(?string $fileName = null): BackgroundActivityStatus
    {
        $fileName = $fileName ?? $this->generateSampleFileName();

        return BackgroundActivityStatus::from(
            $this->surveySampleDataDownloadEndpoint->requestDownload($this->surveyId, $fileName)
        );
    }

    /**
     * Return SurveySampleResource for the specified survey
     */
    public function for(int $interviewId): SurveySampleResource
    {
        $surveySampleResource = new SurveySampleResource($this->surveySampleEndpoint, $this->surveySampleCollectionEndpoint);

        return $surveySampleResource
            ->setSurveyId($this->surveyId)
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
            $this->surveyId,
            now()->format('Ymd_His')
        );
    }
}
