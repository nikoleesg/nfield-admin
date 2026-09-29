<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\RequestConfigurationCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Requests\RequestConfigurationModel;

/**
 * The *REQUEST command configurations, reached through
 * `NfieldManager::requestConfigurations()` (`/v2/requests` in the API).
 *
 * Services mirror the endpoint naming: this pairs with
 * RequestConfigurationCollectionEndpoint, and {@see RequestConfigurationService}
 * with RequestConfigurationEndpoint.
 */
class RequestConfigurationCollectionService
{
    public function __construct(
        protected RequestConfigurationCollectionEndpointInterface $requestConfigurationCollectionEndpoint,
    ) {}

    /** @return Collection<int, RequestConfigurationModel> */
    public function list(): Collection
    {
        return RequestConfigurationModel::collect($this->requestConfigurationCollectionEndpoint->list(), Collection::class);
    }

    /**
     * The configuration with this name (the name the *REQUEST command uses),
     * or null when there is none.
     */
    public function findByName(string $name): ?RequestConfigurationModel
    {
        $matches = $this->requestConfigurationCollectionEndpoint->list(['name' => $name]);

        return isset($matches[0]) ? RequestConfigurationModel::from($matches[0]) : null;
    }

    /**
     * Create a configuration. The API returns no body; use findByName() to
     * read it back.
     *
     * @param  array<string, mixed>|RequestConfigurationModel  $data
     */
    public function create(array|RequestConfigurationModel $data): void
    {
        $payload = RequestConfigurationModel::from($data)->toArray();

        $this->requestConfigurationCollectionEndpoint->create($payload);
    }

    /**
     * One request configuration, by its id.
     */
    public function forRequestConfiguration(int $requestConfigurationId): RequestConfigurationService
    {
        return app(RequestConfigurationService::class)->setRequestConfigurationId($requestConfigurationId);
    }
}
