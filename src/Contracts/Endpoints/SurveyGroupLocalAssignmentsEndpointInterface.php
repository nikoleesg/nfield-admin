<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyGroupLocalAssignmentsEndpointInterface
{
    /**
     * The local assignments of a survey group.
     *
     * @return list<array<string, mixed>>
     */
    public function list(int $surveyGroupId): array;
}
