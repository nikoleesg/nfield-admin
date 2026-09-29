<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyGroupDirectoryAssignmentsEndpointInterface
{
    /**
     * The directory assignments of a survey group, optionally filtered with OData query options.
     *
     * @param  array<string, mixed>  $query
     * @return list<array<string, mixed>>
     */
    public function list(int $surveyGroupId, array $query = []): array;
}
