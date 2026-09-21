<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyEndpointInterface
{
    /**
     * Retrieves details of a specific survey.
     */
    public function get(string $surveyId): array;

    /**
     * Deletes a specified survey.
     */
    public function destroy(string $surveyId): void;

    /**
     * Update a survey with the specified fields.
     */
    public function updatePartial(string $surveyId, array $surveyUpdateModel): array;

    /**
     * Return the counts for the specified survey.
     */
    public function counts(string $surveyId): array;

    /**
     * Get custom columns for the specified survey.
     */
    public function getCustomColumns(string $surveyId): array;
}
