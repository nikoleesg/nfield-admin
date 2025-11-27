<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyFieldworkEndpointInterface
{
    /**
     * Starts the fieldwork of the survey
     * @param string $surveyId
     * @return bool
     */
    public function start(string $surveyId): bool;

    /**
     * Returns fieldwork status
     * @param string $surveyId
     * @return int
     */
    public function status(string $surveyId): int;

    /**
     * Return survey fieldwork counts
     * @param string $surveyId
     * @return array
     */
    public function counts(string $surveyId): array;

    /**
     * Stop the fieldwork of the survey
     * @param string $surveyId
     * @param array $surveysFieldworkStopRequestModel
     * @return bool
     */
    public function stop(string $surveyId, array $surveysFieldworkStopRequestModel): bool;
}
