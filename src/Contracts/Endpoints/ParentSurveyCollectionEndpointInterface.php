<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface ParentSurveyCollectionEndpointInterface
{
    /**
     * The parent surveys, optionally filtered with OData query options.
     *
     * @param  array<string, mixed>  $query
     * @return list<array<string, mixed>>
     */
    public function list(array $query = []): array;

    /**
     * Create an Online parent survey.
     *
     * @param  array<string, mixed>  $parentSurveyCreateRequestModel
     * @return array<string, mixed>
     */
    public function create(array $parentSurveyCreateRequestModel): array;
}
