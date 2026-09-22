<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Resources;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleEndpointInterface;
use Nikoleesg\NfieldAdmin\Support\CsvParser;

final class SurveySampleResource
{
    protected ?string $surveyId = null;

    protected ?int $interviewId = null;

    public function __construct(
        protected SurveySampleEndpointInterface $surveySampleEndpoint,
    ) {}

    public function setSurveyId(string $surveyId): self
    {
        $this->surveyId = $surveyId;

        return $this;
    }

    public function setInterviewId(int $interviewId): self
    {
        $this->interviewId = $interviewId;

        return $this;
    }

    public function getSampleRecord(): ?array
    {
        // Get raw CSV data from endpoint
        $rawCsvData = $this->surveySampleEndpoint->get($this->surveyId, $this->interviewId);

        // Parse CSV into array
        $parsed = CsvParser::parse($rawCsvData);

        return $parsed[0] ?? null;
    }

    public function deleteSampleData(array $sampleFilterModel): array
    {
        return $this->surveySampleEndpoint->destroy($this->surveyId, $sampleFilterModel);
    }

    public function updateSampleRecord(array $surveyUpdateSampleRecordModel): array
    {
        return $this->surveySampleEndpoint->update($this->surveyId, $surveyUpdateSampleRecordModel);
    }
}
