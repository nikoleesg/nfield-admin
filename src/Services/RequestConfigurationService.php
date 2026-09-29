<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\RequestConfigurationCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\RequestConfigurationEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\RequestConfigurationScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Requests\RequestConfigurationModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToRequestConfiguration;

/**
 * One *REQUEST command configuration, reached through
 * `NfieldManager::requestConfigurations()->forRequestConfiguration($id)`.
 *
 * Services mirror the endpoint naming: this pairs with
 * RequestConfigurationEndpoint, and {@see RequestConfigurationCollectionService}
 * with RequestConfigurationCollectionEndpoint.
 */
class RequestConfigurationService implements RequestConfigurationScopedInterface
{
    use ScopedToRequestConfiguration;

    public function __construct(
        protected RequestConfigurationEndpointInterface $requestConfigurationEndpoint,
        protected RequestConfigurationCollectionEndpointInterface $requestConfigurationCollectionEndpoint,
    ) {}

    public function get(): RequestConfigurationModel
    {
        return RequestConfigurationModel::from(
            $this->requestConfigurationEndpoint->get($this->getRequestConfigurationId())
        );
    }

    /**
     * Replace this configuration. The API takes the id in the body of a PUT to
     * the collection; it is filled in from the scope, so callers never repeat
     * it and cannot update another configuration by mistake.
     *
     * @param  array<string, mixed>|RequestConfigurationModel  $data
     */
    public function update(array|RequestConfigurationModel $data): RequestConfigurationModel
    {
        $model = RequestConfigurationModel::from($data);
        $model->id = $this->getRequestConfigurationId();

        return RequestConfigurationModel::from(
            $this->requestConfigurationCollectionEndpoint->update($model->toArray())
        );
    }

    public function delete(): void
    {
        $this->requestConfigurationEndpoint->delete($this->getRequestConfigurationId());
    }
}
