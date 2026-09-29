<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SubscriptionEndpointInterface
{
    /**
     * @return array<string, mixed>
     */
    public function get(string $name): array;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(string $name, array $data): void;

    public function delete(string $name): void;
}
