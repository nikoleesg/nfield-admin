<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Exceptions\CannotCreateData;
use Nikoleesg\NfieldAdmin\Endpoints\BaseEndpoint;
use Nikoleesg\NfieldAdmin\Data\ResponseCodeDTO;

class ResponseCodesEndpoint extends BaseEndpoint
{
    protected string $resourcePath = 'v2/ResponseCodes';

    public function index()
    {
        $resourcePath = $this->resourcePath;

        $response = $this->httpClient->get($resourcePath);

        $responseCodes = json_decode($response->body(), true);


        return ResponseCodeDTO::collection($responseCodes);
    }
}
