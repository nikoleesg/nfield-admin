<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Resources;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SampleFilterModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SampleUpdateStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SurveyUpdateSampleRecordModel;
use Nikoleesg\NfieldAdmin\Support\CsvParser;

final class SurveySampleResource
{
    protected ?string $surveyId = null;

    protected ?int $interviewId = null;

    public function __construct(
        protected SurveySampleEndpointInterface $surveySampleEndpoint,
        protected SurveySampleCollectionEndpointInterface $surveySampleCollectionEndpoint,
    ) {}

    public function setSurveyId(string $surveyId): static
    {
        $this->surveyId = $surveyId;

        return $this;
    }

    public function setInterviewId(int $interviewId): static
    {
        $this->interviewId = $interviewId;

        return $this;
    }

    /**
     * The sample record for this interview.
     *
     * Sample columns are defined per survey, so the record has no fixed shape
     * and is returned keyed by its CSV header columns.
     *
     * @return Collection<string, string>|null
     */
    public function getSampleRecord(): ?Collection
    {
        // Get raw CSV data from endpoint
        $rawCsvData = $this->surveySampleEndpoint->get($this->surveyId, $this->interviewId);

        // Parse CSV into array
        /** @var array<int, array<string, string>> $parsed */
        $parsed = CsvParser::parse($rawCsvData);

        return isset($parsed[0]) ? new Collection($parsed[0]) : null;
    }

    /**
     * @param  iterable<int, array|SampleFilterModel>  $filters
     */
    public function deleteSampleData(iterable $filters): BackgroundActivityStatus
    {
        $payload = [];

        foreach ($filters as $filter) {
            $payload[] = SampleFilterModel::from($filter)->toArray();
        }

        return BackgroundActivityStatus::from(
            $this->surveySampleCollectionEndpoint->destroy($this->surveyId, $payload)
        );
    }

    public function updateSampleRecord(array|SurveyUpdateSampleRecordModel $data): SampleUpdateStatus
    {
        $payload = SurveyUpdateSampleRecordModel::from($data)->toArray();

        return SampleUpdateStatus::from(
            $this->surveySampleCollectionEndpoint->update($this->surveyId, $payload)
        );
    }
}
