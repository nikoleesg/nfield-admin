<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyResponseCodeCollectionEndpointInterface
{
    /**
     * All response codes of a survey.
     *
     * @return list<array<string, mixed>>
     */
    public function list(string $surveyId): array;

    /**
     * The survey's response codes matching an OData query.
     *
     * @param  array<string, mixed>  $data
     * @return list<array<string, mixed>>
     */
    public function find(string $surveyId, array $data): array;

    /**
     * Create a response code on a survey.
     *
     * @param  array<string, mixed>  $surveyResponseCodeModel
     * @return array<string, mixed>
     */
    public function create(string $surveyId, array $surveyResponseCodeModel): array;
}
