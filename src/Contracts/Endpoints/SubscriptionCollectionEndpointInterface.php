<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SubscriptionCollectionEndpointInterface
{
    public function list(): array;

    public function create(array $data): array;
}
