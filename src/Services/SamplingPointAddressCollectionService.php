<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAddressCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SamplingPointScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\Addresses\AddressModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSamplingPoint;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * A sampling point's addresses, reached through `$samplingPoint->addresses()`.
 *
 * Services mirror the endpoint naming: this pairs with
 * SamplingPointAddressCollectionEndpoint, and {@see SamplingPointAddressService}
 * with SamplingPointAddressEndpoint.
 */
class SamplingPointAddressCollectionService implements SamplingPointScopedInterface
{
    use ScopedToSamplingPoint;
    use ScopedToSurvey;

    public function __construct(
        protected SamplingPointAddressCollectionEndpointInterface $samplingPointAddressCollectionEndpoint,
    ) {}

    /** @return Collection<int, AddressModel> */
    public function list(): Collection
    {
        return $this->find();
    }

    /**
     * @param  array<string, mixed>  $filter
     * @return Collection<int, AddressModel>
     */
    public function find(array $filter = []): Collection
    {
        return AddressModel::collect(
            $this->samplingPointAddressCollectionEndpoint->find($this->getSurveyId(), $this->getSamplingPointId(), $filter),
            Collection::class
        );
    }

    /**
     * @param  array<string, mixed>|AddressModel  $data
     */
    public function create(array|AddressModel $data): AddressModel
    {
        $payload = AddressModel::from($data)->toArray();

        return AddressModel::from(
            $this->samplingPointAddressCollectionEndpoint->create($this->getSurveyId(), $this->getSamplingPointId(), $payload)
        );
    }

    /**
     * One address of this sampling point.
     */
    public function forAddress(string $addressId): SamplingPointAddressService
    {
        return app(SamplingPointAddressService::class)
            ->setSurveyId($this->getSurveyId())
            ->setSamplingPointId($this->getSamplingPointId())
            ->setAddressId($addressId);
    }
}
