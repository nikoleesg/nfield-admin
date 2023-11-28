<?php


namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Endpoints\v1;
use Nikoleesg\NfieldAdmin\Endpoints\v2;

class DomainService
{
    protected v2\ResponseCodesEndpoint $responseCodesEndpoint;

    public function __construct()
    {
        $this->responseCodesEndpoint = new v2\ResponseCodesEndpoint();
    }

    public function getResponseCodes()
    {
        return $this->responseCodesEndpoint->index();
    }

}
