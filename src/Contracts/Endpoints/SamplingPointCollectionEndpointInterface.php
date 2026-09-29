<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SamplingPointCollectionEndpointInterface
{
    /**
     * Get a list of all sampling points for a survey.
     *
     * @return list<array<string, mixed>>
     */
    public function list(string $surveyId): array;

    /**
     * Get a list of sampling points for a survey, filtered and sorted using standard OData syntax.
     *
     * @param  array<string, mixed>  $data
     * @return list<array<string, mixed>>
     */
    public function find(string $surveyId, array $data = []): array;

    /**
     * Create a new sampling point.
     *
     * @param  array<string, mixed>  $samplingPointCreateRequestModel
     * @return array<string, mixed>
     */
    public function create(string $surveyId, array $samplingPointCreateRequestModel): array;
}
