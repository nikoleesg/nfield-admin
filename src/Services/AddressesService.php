<?php

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Nikoleesg\NfieldAdmin\Data\AddressData;
use Spatie\LaravelData\DataCollection;
use Nikoleesg\NfieldAdmin\Endpoints\v1\AddressesEndpoint;

class AddressesService
{
    protected AddressesEndpoint $addressesEndpoint;

    protected ?string $surveyId;

    protected ?string $samplingPointId;

    public function __construct(?string $surveyId = null, ?string $samplingPointId = null)
    {
        $this->surveyId = $surveyId;

        $this->samplingPointId = $samplingPointId;

        if ($this->isSurveyConfigured() && $this->isSamplingPointConfigured()) {
            $this->initEndpoint();
        }
    }

    protected function initEndpoint(): self
    {
        $this->addressesEndpoint = new AddressesEndpoint($this->surveyId, $this->samplingPointId);

        return $this;
    }

    public function isSurveyConfigured(): bool
    {
        return isset($this->surveyId);
    }

    public function isSamplingPointConfigured(): bool
    {
        return isset($this->samplingPointId);
    }

    public function setSurvey(string $surveyId): self
    {
        $this->surveyId = $surveyId;

        if ($this->isSurveyConfigured() && $this->isSamplingPointConfigured()) {
            $this->initEndpoint();
        }

        return $this;
    }

    public function setSamplingPoint(string $samplingPointId): self
    {
        $this->samplingPointId = $samplingPointId;

        if ($this->isSurveyConfigured() && $this->isSamplingPointConfigured()) {
            $this->initEndpoint();
        }

        return $this;
    }

    public function get(?string $addressId = null)
    {
        if (!is_null($addressId)) {
            return $this->getAddress($addressId);
        }

        return $this->addressesEndpoint->index();
    }

    public function getAddress(string $addressId)
    {
        return $this->addressesEndpoint->show($addressId);
    }

    public function create(AddressData $addressData): AddressData
    {
        return $this->addressesEndpoint->store($addressData);
    }

    public function add(string $details, string $addressId = null): AddressData
    {
        $addressData = AddressData::from([
            'address_id' => null,
            'details' => $details,
        ]);

        return $this->create($addressData);
    }

    public function delete(string|AddressData $address): bool
    {
        if ($address instanceof AddressData) {
            $addressId = $address->id;
        } else {
            $addressId = $address;
        }

        return $this->addressesEndpoint->destroy($addressId);
    }

    public function count(): int
    {
        return $this->addressesEndpoint->count();
    }
}
