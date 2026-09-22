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
    protected ?string $surveyId = null;

    public function __construct(
        protected SurveySampleCollectionEndpointInterface $surveySampleCollectionEndpoint,
        protected SurveySampleEndpointInterface $surveySampleEndpoint,
    ) {}

    public function setSurveyId(string $surveyId): self
    {
        $this->surveyId = $surveyId;

        return $this;
    }

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

    public function blockSampleData(): void {}

    /**
     * Create a survey sample (Online)
     */
    public function createSampleData(Collection $surveyCreateSampleColumnModelCollection): Collection
    {
        $response = $this->surveySampleCollectionEndpoint->create($this->surveyId, $surveyCreateSampleColumnModelCollection->toArray());

        return collect($response);
    }

    // TODO:
    public function resetSampleData(): void {}

    public function clearSampleDataColumns(): void {}

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
