<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyPublicIdsEndpointInterface
{
    /**
     * @return list<array<string, mixed>>
     */
    public function list(string $surveyId): array;

    /**
     * @param  list<array<string, mixed>>  $models
     */
    public function update(string $surveyId, array $models): void;
}
