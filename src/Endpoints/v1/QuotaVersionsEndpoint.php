<?php


namespace Nikoleesg\NfieldAdmin\Endpoints\v1;


use Illuminate\Support\Str;
use Spatie\LaravelData\DataCollection;
use Nikoleesg\NfieldAdmin\HttpClient;
use Nikoleesg\NfieldAdmin\Data\QuotaFrameVersionData;

class QuotaVersionsEndpoint
{
    protected string $resourcePath = 'v1/Surveys/{surveyId}/QuotaVersions';

    protected HttpClient $httpClient;

    public function __construct(string $surveyId)
    {
        $this->resourcePath = Str::replace('{surveyId}', $surveyId, $this->resourcePath);

        $this->httpClient = new HttpClient();
    }

    public function index(): DataCollection
    {
        $resourcePath = $this->resourcePath;

        $response = $this->httpClient->get($resourcePath);

        $quotaVersions = json_decode($response->body(), true);

        return QuotaFrameVersionData::collection($quotaVersions);
    }

    public function show(string $eTag)
    {
        $resourcePath = $this->resourcePath . "/$eTag";

        $response = $this->httpClient->get($resourcePath);

        $quotaFrame = json_decode($response->body(), true);

        return $quotaFrame;

//        try {
//            $addressData = AddressData::from($address);
//
//        } catch (CannotCreateData $exception) {
//            $addressData = AddressData::optional(null);
//        }
//
//        return $addressData;
    }
}
