<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAddressCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAddressEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\Addresses\AddressModel;
use Nikoleesg\NfieldAdmin\Resources\SamplingPointAddressResource;

class SamplingPointAddressService
{
    public function __construct(
        protected SamplingPointAddressCollectionEndpointInterface $samplingPointCollectionEndpoint,
        protected SamplingPointAddressEndpointInterface $samplingPointAddressEndpoint,
        protected readonly string $surveyId,
        protected readonly string $samplingPointId,
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
            $this->samplingPointCollectionEndpoint->find($this->surveyId, $this->samplingPointId, $filter),
            Collection::class
        );
    }

    public function createAddress(array|AddressModel $data): AddressModel
    {
        $payload = AddressModel::from($data)->toArray();

        return AddressModel::from(
            $this->samplingPointCollectionEndpoint->create($this->surveyId, $this->samplingPointId, $payload)
        );
    }

    /**
     * Return a SamplingPointAddressResource for a specific survey and sampling point
     */
    public function for(string $addressId): SamplingPointAddressResource
    {
        return (new SamplingPointAddressResource($this->samplingPointAddressEndpoint))
            ->setSurveyId($this->surveyId)
            ->setSamplingPointId($this->samplingPointId)
            ->setAddressId($addressId);
    }
}
