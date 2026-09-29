<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyPublishEndpointInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getPublishState(string $surveyId): array;

    /**
     * @param  array<string, mixed>  $model
     */
    public function publish(string $surveyId, array $model): void;

    /**
     * @param  array<string, mixed>  $model
     * @return array<string, mixed>
     */
    public function startPublish(string $surveyId, array $model): array;
}
