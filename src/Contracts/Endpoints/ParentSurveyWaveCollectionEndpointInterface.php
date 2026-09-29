<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface ParentSurveyWaveCollectionEndpointInterface
{
    /**
     * A parent survey's waves, optionally filtered with OData query options.
     *
     * @param  array<string, mixed>  $query
     * @return list<array<string, mixed>>
     */
    public function list(string $parentSurveyId, array $query = []): array;

    /**
     * Create an Online wave under a parent survey.
     *
     * @param  array<string, mixed>  $parentSurveyWaveCreateRequestModel
     * @return array<string, mixed>
     */
    public function create(string $parentSurveyId, array $parentSurveyWaveCreateRequestModel): array;
}
