<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyCollectionEndpointInterface
{
    /**
     * Retrieves a list of surveys.
     *
     * @return list<array<string, mixed>>
     */
    public function list(): array;

    /**
     * Retrieves a list of surveys with filtered and sorted using standard OData syntax.
     *
     * @param  array<string, mixed>  $data
     * @return list<array<string, mixed>>
     */
    public function find(array $data): array;

    /**
     * Create a new survey
     *
     * @param  array<string, mixed>  $surveyModel
     * @return array<string, mixed>
     */
    public function create(array $surveyModel): array;

    /**
     * Creates a new survey from a blueprint survey.
     *
     * @param  array<string, mixed>  $surveyFromBlueprintModel
     * @return array<string, mixed>
     */
    public function createFromBlueprint(array $surveyFromBlueprintModel): array;

    /**
     * Search respondent across surveys.
     *
     * @return list<array<string, mixed>>
     */
    public function search(string $value): array;
}
