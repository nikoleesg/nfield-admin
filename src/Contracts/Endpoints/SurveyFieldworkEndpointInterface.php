<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyFieldworkEndpointInterface
{
    /**
     * Starts the fieldwork of the survey
     */
    public function start(string $surveyId): void;

    /**
     * Returns fieldwork status
     */
    public function status(string $surveyId): int;

    /**
     * Return survey fieldwork counts
     */
    public function counts(string $surveyId): array;

    /**
     * Stop the fieldwork of the survey
     */
    public function stop(string $surveyId, array $surveysFieldworkStopRequestModel): void;
}
