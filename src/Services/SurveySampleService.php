<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SampleUploadStatus;
use Nikoleesg\NfieldAdmin\Resources\SurveySampleResource;
use Nikoleesg\NfieldAdmin\Support\CsvParser;
use RuntimeException;

class SurveySampleService
{
    public function __construct(
        protected SurveySampleCollectionEndpointInterface $surveySampleCollectionEndpoint,
        protected SurveySampleEndpointInterface $surveySampleEndpoint,
        protected readonly string $surveyId,
    ) {}

    /**
     * Download and parse sample data from the survey
     *
     * @return array Array of sample records with headers as keys
     *
     * @throws RuntimeException If CSV parsing fails
     */
    public function downloadSampleData(): array
    {
        // Get raw CSV data from endpoint
        $rawCsvData = $this->surveySampleCollectionEndpoint->download($this->surveyId);

        // Parse CSV into array
        return CsvParser::parse($rawCsvData);
    }

    public function uploadSampleData(string $sampleData, ?string $fileName = null): SampleUploadStatus
    {
        $fileName = $fileName ?? $this->generateSampleFileName();
        $response = $this->surveySampleCollectionEndpoint->upload($this->surveyId, $sampleData, $fileName);

        return SampleUploadStatus::from($response);
    }

    public function blockSampleData(array $sampleFilterModel): array
    {
        return $this->surveySampleCollectionEndpoint->block($this->surveyId, $sampleFilterModel);
    }

    /**
     * Create a survey sample (Online)
     */
    public function createSampleData(Collection $surveyCreateSampleColumnModelCollection): Collection
    {
        $response = $this->surveySampleCollectionEndpoint->create($this->surveyId, $surveyCreateSampleColumnModelCollection->toArray());

        return collect($response);
    }

    public function resetSampleData(array $sampleFilterModel): array
    {
        return $this->surveySampleCollectionEndpoint->reset($this->surveyId, $sampleFilterModel);
    }

    public function clearSampleDataColumns(array $clearSurveySampleModel): array
    {
        return $this->surveySampleCollectionEndpoint->clear($this->surveyId, $clearSurveySampleModel);
    }

    public function requestSampleDownload(?string $fileName = null): array
    {
        $fileName = $fileName ?? $this->generateSampleFileName();

        return $this->surveySampleCollectionEndpoint->requestDownload($this->surveyId, $fileName);
    }

    /**
     * Return SurveySampleResource for the specified survey
     */
    public function for(int $interviewId): SurveySampleResource
    {
        $surveySampleResource = new SurveySampleResource($this->surveySampleEndpoint);

        if ($this->surveyId !== null) {
            $surveySampleResource->setSurveyId($this->surveyId);
        }

        $surveySampleResource->setInterviewId($interviewId);

        return $surveySampleResource;
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
