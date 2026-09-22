<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

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

    public function listAddresses(): array
    {
        return $this->samplingPointCollectionEndpoint->find($this->surveyId, $this->samplingPointId, []);
    }

    public function findAddresses(array $data = []): array
    {
        return $this->samplingPointCollectionEndpoint->find($this->surveyId, $this->samplingPointId, $data);
    }

    public function createAddress(AddressModel $data): AddressModel
    {
        $createdAddress = $this->samplingPointCollectionEndpoint->create($this->surveyId, $this->samplingPointId, $data->toArray());

        return AddressModel::from($createdAddress);
    }

    /**
     * Return a SaplingPointAddressResource for a specific survey and sampling point
     */
    public function for(string $addressId): SamplingPointAddressResource
    {
        $samplingPointAddress = new SamplingPointAddressResource($this->samplingPointAddressEndpoint);

        if ($this->surveyId !== null) {
            $samplingPointAddress->setSurveyId($this->surveyId);
        }

        if ($this->samplingPointId !== null) {
            $samplingPointAddress->setSamplingPointId($this->samplingPointId);
        }

        $samplingPointAddress->setAddressId($addressId);

        return $samplingPointAddress;
    }
}
