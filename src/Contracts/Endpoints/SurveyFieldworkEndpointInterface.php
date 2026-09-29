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
     *
     * @return array<string, mixed>
     */
    public function counts(string $surveyId): array;

    /**
     * Stop the fieldwork of the survey
     *
     * @param  array<string, mixed>  $surveysFieldworkStopRequestModel
     */
    public function stop(string $surveyId, array $surveysFieldworkStopRequestModel): void;
}
