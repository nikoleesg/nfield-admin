<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Resources;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAddressEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\AddressScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\Addresses\AddressModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToAddress;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSamplingPoint;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

class SamplingPointAddressResource implements AddressScopedInterface
{
    use ScopedToAddress;
    use ScopedToSamplingPoint;
    use ScopedToSurvey;

    public function __construct(
        protected SamplingPointAddressEndpointInterface $samplingPointAddressEndpoint
    ) {}

    public function getAddress(): AddressModel
    {
        return AddressModel::from(
            $this->samplingPointAddressEndpoint->get($this->getSurveyId(), $this->getSamplingPointId(), $this->getAddressId())
        );
    }

    public function deleteAddress(): void
    {
        $this->samplingPointAddressEndpoint->delete($this->getSurveyId(), $this->getSamplingPointId(), $this->getAddressId());
    }
}
