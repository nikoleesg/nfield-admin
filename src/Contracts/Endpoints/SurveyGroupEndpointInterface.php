<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyGroupEndpointInterface
{
    /**
     * One survey group.
     *
     * @return array<string, mixed>
     */
    public function get(int $surveyGroupId): array;
}
