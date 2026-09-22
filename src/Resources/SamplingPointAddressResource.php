<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Resources;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAddressEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\Addresses\AddressModel;

class SamplingPointAddressResource
{
    protected ?string $surveyId = null;

    protected ?string $samplingPointId = null;

    protected ?string $addressId = null;

    public function __construct(
        protected SamplingPointAddressEndpointInterface $samplingPointAddressEndpoint
    ) {}

    public function setSurveyId(string $surveyId): static
    {
        $this->surveyId = $surveyId;

        return $this;
    }

    public function setSamplingPointId(string $samplingPointId): static
    {
        $this->samplingPointId = $samplingPointId;

        return $this;
    }

    public function setAddressId(string $addressId): static
    {
        $this->addressId = $addressId;

        return $this;
    }

    public function getAddress(): AddressModel
    {
        return AddressModel::from(
            $this->samplingPointAddressEndpoint->get($this->surveyId, $this->samplingPointId, $this->addressId)
        );
    }

    public function deleteAddress(): void
    {
        $this->samplingPointAddressEndpoint->delete($this->surveyId, $this->samplingPointId, $this->addressId);
    }
}
