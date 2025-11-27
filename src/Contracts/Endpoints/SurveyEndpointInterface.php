<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyEndpointInterface
{
    /**
     * Retrieves details of a specific survey.
     * @param string $surveyId
     * @return array
     */
    public function get(string $surveyId): array;

    /**
     * Deletes a specified survey.
     * @param string $surveyId
     * @return void
     */
    public function destroy(string $surveyId): void;

    /**
     * Update a survey with the specified fields.
     * @param string $surveyId
     * @param array $surveyUpdateModel
     * @return array
     */
    public function updatePartial(string $surveyId, array $surveyUpdateModel): array;

    /**
     * Return the counts for the specified survey.
     * @param string $surveyId
     * @return array
     */
    public function counts(string $surveyId): array;

    /**
     * Get custom columns for the specified survey.
     * @param string $surveyId
     * @return array
     */
    public function getCustomColumns(string $surveyId): array;

}
