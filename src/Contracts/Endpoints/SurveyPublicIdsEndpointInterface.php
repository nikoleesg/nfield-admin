<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyPublicIdsEndpointInterface
{
    public function list(string $surveyId): array;

    public function update(string $surveyId, array $models): void;
}
