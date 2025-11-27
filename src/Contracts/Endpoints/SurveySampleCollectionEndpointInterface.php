<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveySampleCollectionEndpointInterface
{
    /**
     * Retrieves the sample data for the specified survey
     * @param string $surveyId
     * @return string
     */
    public function download(string $surveyId): string;

    /**
     * Uploads the sample data for specified survey
     * @param string $surveyId
     * @param string $sampleData
     * @return array
     */
    public function upload(string $surveyId, string $sampleData): array;

    /**
     * Blocks sample data for a survey based on survey id and a filter
     * @param string $surveyId
     * @param array $sampleFilterModel
     * @return array
     */
    public function block(string $surveyId, array $sampleFilterModel): array;

    /**
     * Creates a sample record for the specified survey
     * @param string $surveyId
     * @param array $surveyCreateSampleColumnModel
     * @return array
     */
    public function create(string $surveyId, array $surveyCreateSampleColumnModel): array;

    /**
     * Resets sample data for a survey based on the survey id and a filter
     * @param string $surveyId
     * @param array $sampleFilterModel
     * @return array
     */
    public function reset(string $surveyId, array $sampleFilterModel): array;

    /**
     * Clears the specified columns in sample data for a survey based on survey id and a filter
     * @param string $surveyId
     * @param array $clearSurveySampleModel
     * @return array
     */
    public function clear(string $surveyId, array $clearSurveySampleModel): array;

    /**
     * Create a request for a sample data download
     * @param string $surveyId
     * @param string $fileName
     * @return array
     */
    public function requestDownload(string $surveyId, string $fileName): array;
}
