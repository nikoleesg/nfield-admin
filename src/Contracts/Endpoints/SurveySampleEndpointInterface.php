<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveySampleEndpointInterface
{
    /**
     * Delete the specified survey's SampleData
     */
    public function destroy(string $surveyId, array $sampleFilterModel): array;

    /**
     * Retrieves a single sample record for the specified survey
     */
    public function get(string $surveyId, int $interviewId): string;

    /**
     * Update Sample Record
     */
    public function update(string $surveyId, array $surveyUpdateSampleRecordModel): array;
}
