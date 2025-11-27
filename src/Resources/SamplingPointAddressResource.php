<?php

namespace Nikoleesg\NfieldAdmin\Resources;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAddressEndpointInterface;

class SamplingPointAddressResource
{
    protected ?string $surveyId = null;
    protected ?string $samplingPointId = null;
    protected ?string $addressId = null;

    public function __construct(
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

    public function setAddressId(string $addressId): self
    {
        $this->addressId = $addressId;
        return $this;
    }

    public function getAddress(): array
    {
        return $this->samplingPointAddressEndpoint->get($this->surveyId, $this->samplingPointId, $this->addressId);
    }

    public function deleteAddress(): bool
    {
        return $this->samplingPointAddressEndpoint->delete($this->surveyId, $this->samplingPointId, $this->addressId);
    }
}
