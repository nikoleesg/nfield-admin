<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyResourceEndpoint
{
    public function get(string $surveyId): array;

    public function destroy(string $surveyId): void;

    public function updatePartial(string $surveyId, array $surveyUpdateModel): array;

    public function counts(string $surveyId): array;

    public function getCustomColumns(string $surveyId): array;

    public function requestDataDownload(string $surveyId, array $surveyDataRequestModel): array;
}
