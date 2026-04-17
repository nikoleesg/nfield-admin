<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyPublishEndpointInterface
{
    public function getPublishState(string $surveyId): array;

    public function publish(string $surveyId, array $model): bool;

    public function startPublish(string $surveyId, array $model): array;
}
