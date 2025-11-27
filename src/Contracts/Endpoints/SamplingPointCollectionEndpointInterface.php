<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SamplingPointCollectionEndpointInterface
{
    /**
     * Get a list of all sampling points for a survey.
     * @param string $surveyId
     * @return array
     */
    public function list(string $surveyId): array;

    /**
     * Get a list of sampling points for a survey, filtered and sorted using standard OData syntax.
     * @param string $surveyId
     * @param array $data
     * @return array
     */
    public function find(string $surveyId, array $data): array;

    /**
     * Create a new sampling point.
     * @param string $surveyId
     * @param array $samplingPointCreateRequestModel
     * @return array
     */
    public function create(string $surveyId, array $samplingPointCreateRequestModel): array;

    /**
     * Activated a list of spare sampling points so they can be assigned.
     * @param string $surveyId
     * @param array $samplingPointIds
     * @return void
     */
    public function batchActivate(string $surveyId, array $samplingPointIds): void;
}
