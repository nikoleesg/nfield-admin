<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAddressCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAddressEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SamplingPointScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\Addresses\AddressModel;
use Nikoleesg\NfieldAdmin\Resources\SamplingPointAddressResource;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSamplingPoint;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

class SamplingPointAddressService implements SamplingPointScopedInterface
{
    use ScopedToSamplingPoint;
    use ScopedToSurvey;

    public function __construct(
        protected SamplingPointAddressCollectionEndpointInterface $samplingPointCollectionEndpoint,
        protected SamplingPointAddressEndpointInterface $samplingPointAddressEndpoint,
    ) {}

    /** @return Collection<int, AddressModel> */
    public function listAddresses(): Collection
    {
        return $this->findAddresses();
    }

    /** @return Collection<int, AddressModel> */
    public function findAddresses(array $filter = []): Collection
    {
        return AddressModel::collect(
            $this->samplingPointCollectionEndpoint->find($this->getSurveyId(), $this->getSamplingPointId(), $filter),
            Collection::class
        );
    }

    public function createAddress(array|AddressModel $data): AddressModel
    {
        $payload = AddressModel::from($data)->toArray();

        return AddressModel::from(
            $this->samplingPointCollectionEndpoint->create($this->getSurveyId(), $this->getSamplingPointId(), $payload)
        );
    }

    /**
     * Return a SamplingPointAddressResource for a specific survey and sampling point
     */
    public function forAddress(string $addressId): SamplingPointAddressResource
    {
        return (new SamplingPointAddressResource($this->samplingPointAddressEndpoint))
            ->setSurveyId($this->getSurveyId())
            ->setSamplingPointId($this->getSamplingPointId())
            ->setAddressId($addressId);
    }
}
