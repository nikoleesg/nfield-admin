<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SamplingPointCollectionEndpointInterface
{
    /**
     * Get a list of all sampling points for a survey.
     */
    public function list(string $surveyId): array;

    /**
     * Get a list of sampling points for a survey, filtered and sorted using standard OData syntax.
     */
    public function find(string $surveyId, array $data = []): array;

    /**
     * Create a new sampling point.
     */
    public function create(string $surveyId, array $samplingPointCreateRequestModel): array;
}
