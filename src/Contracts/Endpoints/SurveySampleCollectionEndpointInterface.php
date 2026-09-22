<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveySampleCollectionEndpointInterface
{
    /**
     * Retrieves the sample data for the specified survey
     */
    public function download(string $surveyId): string;

    /**
     * Uploads the sample data for specified survey
     */
    public function upload(string $surveyId, string $sampleData, string $fileName = 'sample.csv'): array;

    /**
     * Delete the specified survey's sample data matching the filter
     */
    public function destroy(string $surveyId, array $sampleFilterModel): array;

    /**
     * Blocks sample data for a survey based on survey id and a filter
     */
    public function block(string $surveyId, array $sampleFilterModel): array;

    /**
     * Creates a sample record for the specified survey
     */
    public function create(string $surveyId, array $surveyCreateSampleColumnModel): array;

    /**
     * Resets sample data for a survey based on the survey id and a filter
     */
    public function reset(string $surveyId, array $sampleFilterModel): array;

    /**
     * Clears the specified columns in sample data for a survey based on survey id and a filter
     */
    public function clear(string $surveyId, array $clearSurveySampleModel): array;

    /**
     * Update the sample records matching the supplied filter
     */
    public function update(string $surveyId, array $surveyUpdateSampleRecordModel): array;
}
