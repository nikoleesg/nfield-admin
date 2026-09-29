<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\InterviewScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SampleColumnUpdateModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SampleUpdateStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SurveyUpdateSampleRecordModel;
use Nikoleesg\NfieldAdmin\Support\CsvParser;
use Nikoleesg\NfieldAdmin\Traits\ScopedToInterview;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * The sample record of one interview, reached through
 * `$survey->samples()->forInterview($interviewId)`.
 *
 * Services mirror the endpoint naming: this pairs with SurveySampleEndpoint,
 * and {@see SurveySampleCollectionService} with SurveySampleCollectionEndpoint.
 */
class SurveySampleService implements InterviewScopedInterface
{
    use ScopedToInterview;
    use ScopedToSurvey;

    public function __construct(
        protected SurveySampleEndpointInterface $surveySampleEndpoint,
        protected SurveySampleCollectionEndpointInterface $surveySampleCollectionEndpoint,
    ) {}

    /**
     * The sample record for this interview.
     *
     * Sample columns are defined per survey, so the record has no fixed shape
     * and is returned keyed by its CSV header columns.
     *
     * @return Collection<string, string>|null
     */
    public function get(): ?Collection
    {
        // Get raw CSV data from endpoint
        $rawCsvData = $this->surveySampleEndpoint->get($this->getSurveyId(), $this->getInterviewId());

        // Parse CSV into array
        /** @var array<int, array<string, string>> $parsed */
        $parsed = CsvParser::parse($rawCsvData);

        return isset($parsed[0]) ? new Collection($parsed[0]) : null;
    }

    /**
     * Update this interview's sample record.
     *
     * The record ID is the interview this resource is scoped to, so callers
     * pass only the column updates.
     *
     * @param  iterable<int, array<string, mixed>|SampleColumnUpdateModel>  $columnUpdates
     */
    public function update(iterable $columnUpdates): SampleUpdateStatus
    {
        $updates = [];

        foreach ($columnUpdates as $columnUpdate) {
            $updates[] = SampleColumnUpdateModel::from($columnUpdate);
        }

        $payload = (new SurveyUpdateSampleRecordModel($this->getInterviewId(), $updates))->toArray();

        return SampleUpdateStatus::from(
            $this->surveySampleCollectionEndpoint->update($this->getSurveyId(), $payload)
        );
    }
}
