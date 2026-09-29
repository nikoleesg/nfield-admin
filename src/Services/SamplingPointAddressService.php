<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAddressEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\AddressScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\Addresses\AddressModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToAddress;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSamplingPoint;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * One sampling-point address, reached through
 * `$samplingPoint->addresses()->forAddress($addressId)`.
 *
 * Services mirror the endpoint naming: this pairs with
 * SamplingPointAddressEndpoint, and {@see SamplingPointAddressCollectionService}
 * with SamplingPointAddressCollectionEndpoint.
 */
class SamplingPointAddressService implements AddressScopedInterface
{
    use ScopedToAddress;
    use ScopedToSamplingPoint;
    use ScopedToSurvey;

    public function __construct(
        protected SamplingPointAddressEndpointInterface $samplingPointAddressEndpoint,
    ) {}

    public function get(): AddressModel
    {
        return AddressModel::from(
            $this->samplingPointAddressEndpoint->get($this->getSurveyId(), $this->getSamplingPointId(), $this->getAddressId())
        );
    }

    public function delete(): void
    {
        $this->samplingPointAddressEndpoint->delete($this->getSurveyId(), $this->getSamplingPointId(), $this->getAddressId());
    }
}
