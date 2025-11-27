<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveySampleEndpointInterface
{
    /**
     * Delete the specified survey's SampleData
     * @param string $surveyId
     * @param array $sampleFilterModel
     * @return array
     */
    public function destroy(string $surveyId, array $sampleFilterModel): array;

    /**
     * Retrieves a single sample record for the specified survey
     * @param string $surveyId
     * @param int $interviewId
     * @return string
     */
    public function get(string $surveyId, int $interviewId): string;

    /**
     * Update Sample Record
     * @param string $surveyId
     * @param array $surveyUpdateSampleRecordModel
     * @return array
     */
    public function update(string $surveyId, array $surveyUpdateSampleRecordModel): array;
}
