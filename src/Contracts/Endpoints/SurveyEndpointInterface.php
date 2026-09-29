<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyEndpointInterface
{
    /**
     * Retrieves details of a specific survey.
     *
     * @return array<string, mixed>
     */
    public function get(string $surveyId): array;

    /**
     * Deletes a specified survey.
     */
    public function delete(string $surveyId): void;

    /**
     * Update a survey with the specified fields.
     *
     * @param  array<string, mixed>  $surveyUpdateModel
     * @return array<string, mixed>
     */
    public function update(string $surveyId, array $surveyUpdateModel): array;

    /**
     * Return the counts for the specified survey.
     *
     * @return array<string, mixed>
     */
    public function counts(string $surveyId): array;

    /**
     * Get custom columns for the specified survey.
     *
     * @return list<string>
     */
    public function customColumns(string $surveyId): array;

    /**
     * Activate a list of spare sampling points so they can be assigned.
     *
     * @param  array<string, mixed>  $activateSpareSamplingPointsRequestModel
     * @return array<string, mixed>
     */
    public function batchActivateSamplingPoints(string $surveyId, array $activateSpareSamplingPointsRequestModel): array;
}
