<?php

namespace Nikoleesg\NfieldAdmin\Actions\SamplingPoint;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsJob;
use Nikoleesg\NfieldAdmin\Facades\SamplingPoint;
use Nikoleesg\NfieldAdmin\Data\AddressDTO;

class AddNewAddressToSamplingPointAction
{
    use AsAction, AsJob;

    /**
     * Handle the action.
     * @param string $surveyId
     * @param string $samplingPointId
     * @param AddressDTO $addressDTO
     */
    public function handle(string $surveyId, string $samplingPointId, AddressDTO $addressDTO)
    {
        SamplingPoint::setSurvey($surveyId)->setSamplingPoint($samplingPointId)->createAddress($addressDTO);
    }
    
}
