<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyCollectionEndpointInterface
{
    /**
     * Retrieves a list of surveys.
     */
    public function list(): array;

    /**
     * Retrieves a list of surveys with filtered and sorted using standard OData syntax.
     */
    public function find(array $data): array;

    /**
     * Create a new survey
     */
    public function create(array $surveyModel): array;

    /**
     * Creates a new survey from a blueprint survey.
     */
    public function createFromBlueprint(array $surveyFromBlueprintModel): array;

    /**
     * Search respondent across surveys.
     */
    public function search(string $value): array;
}
