<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SubscriptionCollectionEndpointInterface
{
    /**
     * @return list<array<string, mixed>>
     */
    public function list(): array;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function create(array $data): array;
}
