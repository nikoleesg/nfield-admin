<?php

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAddressCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAddressEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\Addresses\AddressModel;
use Nikoleesg\NfieldAdmin\Resources\SamplingPointAddressResource;

class SamplingPointAddressService
{
    protected ?string $surveyId = null;
    protected ?string $samplingPointId = null;

    public function __construct(
        protected SamplingPointAddressCollectionEndpointInterface $samplingPointCollectionEndpoint,
        protected SamplingPointAddressEndpointInterface $samplingPointAddressEndpoint
    ) {}

    public function setSurveyId(string $surveyId): self
    {
        $this->surveyId = $surveyId;
        return $this;
    }

    public function setSamplingPointId(string $samplingPointId): self
    {
        $this->samplingPointId = $samplingPointId;
        return $this;
    }

    public function listAddresses(): array
    {
        return $this->samplingPointCollectionEndpoint->find($this->surveyId, $this->samplingPointId);
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
     * @param string $addressId
     * @return SamplingPointAddressResource
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
