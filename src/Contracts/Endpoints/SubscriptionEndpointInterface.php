<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SubscriptionEndpointInterface
{
    public function get(string $name): array;

    public function updatePartial(string $name, array $data): void;

    public function destroy(string $name): void;
}
