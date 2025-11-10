<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyCollectionEndpoint
{
    public function all(): array;

    public function filter(array $query): array;

    public function create(array $surveyModel): array;

    public function search(string $value): array;
}
