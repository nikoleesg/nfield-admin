<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyResourceUsageEndpointInterface
{
    /**
     * The resource usage of every survey.
     *
     * @return list<array<string, mixed>>
     */
    public function list(): array;

    /**
     * The resource usage of the surveys matching an OData query.
     *
     * @param  array<string, mixed>  $data
     * @return list<array<string, mixed>>
     */
    public function find(array $data): array;
}
