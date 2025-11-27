<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyCollectionEndpointInterface
{
    /**
     * Retrieves a list of surveys.
     * @return array
     */
    public function list(): array;

    /**
     * Retrieves a list of surveys with filtered and sorted using standard OData syntax.
     * @param array $data
     * @return array
     */
    public function find(array $data): array;

    /**
     * Create a new survey
     * @param array $surveyModel
     * @return array
     */
    public function create(array $surveyModel): array;

    /**
     * Creates a new survey from a blueprint survey.
     * @param array $surveyFromBlueprintModel
     * @return array
     */
    public function clone(array $surveyFromBlueprintModel): array;

    /**
     * Search respondent across surveys.
     * @param string $value
     * @return array
     */
    public function search(string $value): array;
}
