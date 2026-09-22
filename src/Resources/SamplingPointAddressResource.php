<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Resources;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAddressEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SamplingPointScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\Addresses\AddressModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSamplingPoint;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

class SamplingPointAddressResource implements SamplingPointScopedInterface
{
    use ScopedToSamplingPoint;
    use ScopedToSurvey;

    protected ?string $addressId = null;

    public function __construct(
        protected SamplingPointAddressEndpointInterface $samplingPointAddressEndpoint
    ) {}

    public function setAddressId(string $addressId): static
    {
        $this->addressId = $addressId;

        return $this;
    }

    public function getAddress(): AddressModel
    {
        return AddressModel::from(
            $this->samplingPointAddressEndpoint->get($this->getSurveyId(), $this->getSamplingPointId(), $this->addressId)
        );
    }

    public function deleteAddress(): void
    {
        $this->samplingPointAddressEndpoint->delete($this->getSurveyId(), $this->getSamplingPointId(), $this->addressId);
    }
}
