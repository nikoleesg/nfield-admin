<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v1;

use Nikoleesg\NfieldAdmin\Data\AddressDTO;
use Nikoleesg\NfieldAdmin\HttpClient;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Exceptions\CannotCreateData;
use Nikoleesg\NfieldAdmin\Data\AddressData;

class AddressesEndpoint
{
    protected string $resourcePath = 'v1/Surveys/{surveyId}/SamplingPoints/{samplingPointId}/Addresses';

    protected HttpClient $httpClient;

    public function __construct(string $surveyId, string $samplingPointId)
    {
        $this->resourcePath = str_replace(['{surveyId}', '{samplingPointId}'], [$surveyId, $samplingPointId], $this->resourcePath);

        $this->httpClient = new HttpClient();
    }

    /**
     * This method retrieves a list of Addresses.
     *
     * @return DataCollection
     */
    public function index(): DataCollection
    {
        $resourcePath = $this->resourcePath;

        $response = $this->httpClient->get($resourcePath);

        $addresses = json_decode($response->body(), true);

        return AddressDTO::collection($addresses);
    }

    /**
     * Retrieve the details of a single address.
     *
     * @param string $addressId
     * @return AddressDTO
     */
    public function show(string $addressId): AddressDTO
    {
        $resourcePath = $this->resourcePath."/$addressId";

        $response = $this->httpClient->get($resourcePath);

        $address = json_decode($response->body(), true);

        try {
            $addressData = AddressDTO::from($address);

        } catch (CannotCreateData $exception) {
            $addressData = AddressDTO::optional(null);
        }

        return $addressData;
    }

    public function destroy(string $addressId): mixed
    {
        $resourcePath = $this->resourcePath . "/$addressId";

        $response = $this->httpClient->delete($resourcePath);

        return empty($response->body());
    }

    public function update()
    {

    }

    public function store(AddressDTO $addressData): AddressDTO
    {
        $resourcePath = $this->resourcePath;

        // Transform to Nfield API SurveyModel
        $addressModel = array_change_key_casing($addressData->toArray(), CASE_STUDLY);

        $response = $this->httpClient->post($resourcePath, true, $addressModel);

        $address = json_decode($response->body(), true);

        try {
            $addressData = AddressDTO::from($address);
        } catch (CannotCreateData $exception) {
            $addressData = AddressDTO::optional(null);
        }

        return $addressData;
    }

    /**
     * Returns the number of addresses at the sampling point.
     *
     * @return int
     */
    public function count(): int
    {
        $resourcePath = $this->resourcePath . "/Count";

        $response = $this->httpClient->get($resourcePath);

        $addressCnt = json_decode($response->body(), true);

        return $addressCnt;
    }
}
