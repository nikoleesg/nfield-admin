<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Exceptions\CannotCreateData;
use Nikoleesg\NfieldAdmin\Endpoints\BaseEndpoint;
use Nikoleesg\NfieldAdmin\Data\ResponseCodeDTO;
use Nikoleesg\NfieldAdmin\Data\DomainResponseCodeForPatch;
use Illuminate\Support\Arr;

class ResponseCodesEndpoint extends BaseEndpoint
{
    protected string $resourcePath = 'v2/ResponseCodes';

    /**
     * Gets all domain response codes for the domain.
     *
     * @return \Spatie\LaravelData\CursorPaginatedDataCollection|DataCollection|\Spatie\LaravelData\PaginatedDataCollection
     */
    public function index()
    {
        $resourcePath = $this->resourcePath;

        $response = $this->httpClient->get($resourcePath);

        $responseCodes = json_decode($response->body(), true);


        return ResponseCodeDTO::collection($responseCodes);
    }

    /**
     * Create a new domain response code
     *
     * @param ResponseCodeDTO $responseCodeDTO DomainResponseCodeCreateModel
     * @return boolean
     */
    public function create(ResponseCodeDTO $responseCodeDTO)
    {
        $resourcePath = $this->resourcePath;

        $response = $this->httpClient->post($resourcePath, true, $responseCodeDTO->toArray());

        return $response->status() === 200;
    }

    /**
     * Patch the domain's response codes.
     *
     * @param int $responseCodeId The id of the response code to update
     * @param DomainResponseCodeForPatch $domainResponseCodeForPatch
     */
    public function update(int $responseCodeId, DomainResponseCodeForPatch $domainResponseCodeForPatch)
    {
        $resourcePath = $this->resourcePath . "/$responseCodeId";

        $response = $this->httpClient->patch($resourcePath, true, $domainResponseCodeForPatch->toArray());

        return $response->status() === 200;
    }

    /**
     * Delete the domain's response code by id.
     *
     * @param int $responseCodeId
     * @return ResponseCodeDTO $responseCodeDTO
     */
    public function delete(int $responseCodeId)
    {
        $resourcePath = $this->resourcePath . "/$responseCodeId";

        $response = $this->httpClient->delete($resourcePath);

        return $response->status() === 200;

//        $responseCode = json_decode($response->body(), true);
//
//        try {
//            $responseCodeData = ResponseCodeDTO::from($responseCode);
//        } catch (CannotCreateData $exception) {
//            $responseCodeData = ResponseCodeDTO::optional(null);
//        }
//
//        return $responseCodeData;
    }
}
