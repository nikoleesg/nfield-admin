<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyPerformanceEndpointInterface
{
    /**
     * The survey's performance metrics for live interviews.
     *
     * @return array<string, mixed>
     */
    public function live(string $surveyId): array;

    /**
     * The survey's performance metrics for test interviews.
     *
     * @return array<string, mixed>
     */
    public function test(string $surveyId): array;
}
