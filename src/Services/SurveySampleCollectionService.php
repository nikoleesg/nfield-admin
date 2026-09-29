<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleDataDownloadEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\ClearSurveySampleModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SampleFilterModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SampleUploadStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SurveyCreateSampleColumnModel;
use Nikoleesg\NfieldAdmin\Support\CsvParser;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;
use RuntimeException;

/**
 * A survey's sample, reached through `$survey->samples()`.
 *
 * Services mirror the endpoint naming: this pairs with
 * SurveySampleCollectionEndpoint, and {@see SurveySampleService} with
 * SurveySampleEndpoint.
 */
class SurveySampleCollectionService implements SurveyScopedInterface
{
    use ScopedToSurvey;

    public function __construct(
        protected SurveySampleCollectionEndpointInterface $surveySampleCollectionEndpoint,
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
    public function download(): Collection
    {
        $rawCsvData = $this->surveySampleCollectionEndpoint->download($this->getSurveyId());

        return collect(CsvParser::parse($rawCsvData));
    }

    public function upload(string $sampleData, ?string $fileName = null): SampleUploadStatus
    {
        $fileName = $fileName ?? $this->generateSampleFileName();
        $response = $this->surveySampleCollectionEndpoint->upload($this->getSurveyId(), $sampleData, $fileName);

        return SampleUploadStatus::from($response);
    }

    /**
     * @param  iterable<int, array<string, mixed>|SampleFilterModel>  $filters
     */
    public function block(iterable $filters): BackgroundActivityStatus
    {
        return BackgroundActivityStatus::from(
            $this->surveySampleCollectionEndpoint->block($this->getSurveyId(), $this->normaliseFilters($filters))
        );
    }

    /**
     * Create sample columns (Online).
     *
     * @param  iterable<int, array<string, mixed>|SurveyCreateSampleColumnModel>  $columns
     * @return Collection<int, SurveyCreateSampleColumnModel>
     */
    public function createColumns(iterable $columns): Collection
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
     * @param  iterable<int, array<string, mixed>|SampleFilterModel>  $filters
     */
    public function reset(iterable $filters): BackgroundActivityStatus
    {
        return BackgroundActivityStatus::from(
            $this->surveySampleCollectionEndpoint->reset($this->getSurveyId(), $this->normaliseFilters($filters))
        );
    }

    /**
     * @param  array<string, mixed>|ClearSurveySampleModel  $data
     */
    public function clearColumns(array|ClearSurveySampleModel $data): BackgroundActivityStatus
    {
        $payload = ClearSurveySampleModel::from($data)->toArray();

        return BackgroundActivityStatus::from(
            $this->surveySampleCollectionEndpoint->clear($this->getSurveyId(), $payload)
        );
    }

    public function requestDownload(?string $fileName = null): BackgroundActivityStatus
    {
        $fileName = $fileName ?? $this->generateSampleFileName();

        return BackgroundActivityStatus::from(
            $this->surveySampleDataDownloadEndpoint->requestDownload($this->getSurveyId(), $fileName)
        );
    }

    /**
     * Delete the sample records that match the filters.
     *
     * @param  iterable<int, array<string, mixed>|SampleFilterModel>  $filters
     */
    public function delete(iterable $filters): BackgroundActivityStatus
    {
        return BackgroundActivityStatus::from(
            $this->surveySampleCollectionEndpoint->destroy($this->getSurveyId(), $this->normaliseFilters($filters))
        );
    }

    /**
     * The sample record of one interview.
     */
    public function forInterview(int $interviewId): SurveySampleService
    {
        return app(SurveySampleService::class)
            ->setSurveyId($this->getSurveyId())
            ->setInterviewId($interviewId);
    }

    /**
     * The sample filter endpoints take a bare JSON array of filter clauses.
     *
     * @param  iterable<int, array<string, mixed>|SampleFilterModel>  $filters
     * @return list<array<string, mixed>>
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
