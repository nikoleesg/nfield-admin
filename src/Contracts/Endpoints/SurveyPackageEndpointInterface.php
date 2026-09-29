<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyPackageEndpointInterface
{
    /**
     * The survey's published package of the given type (1 live, 2 test).
     *
     * @return array<string, mixed>
     */
    public function get(string $surveyId, int $type): array;
}
