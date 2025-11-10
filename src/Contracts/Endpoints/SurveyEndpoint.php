<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface  SurveyEndpoint
{
    public function all(): array;

    public function filter(array $query): array;

    public function create(array $surveyModel): array;

    public function get(string $surveyId): array;

    public function destroy(string $surveyId): void;

    public function updatePartial(string $surveyId, array $surveyUpdateModel): array;

    public function counts(string $surveyId): array;

    public function getCustomColumns(string $surveyId): array;

    public function requestDataDownload(string $surveyId, array $surveyDataRequestModel): array;

    public function search(string $value): array;
}
