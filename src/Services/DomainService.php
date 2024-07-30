<?php


namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Endpoints\v1;
use Nikoleesg\NfieldAdmin\Endpoints\v2;
use Nikoleesg\NfieldAdmin\Data\ResponseCodeDTO;
use Nikoleesg\NfieldAdmin\Data\DomainResponseCodeForPatch;

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

    public function createResponseCode(ResponseCodeDTO $responseCodeDTO)
    {
        return $this->responseCodesEndpoint->create($responseCodeDTO);
    }

    public function updateResponseCode(int $domainResponseCodeId, DomainResponseCodeForPatch $domainResponseCodeForPatch)
    {
        return $this->responseCodesEndpoint->update($domainResponseCodeId, $domainResponseCodeForPatch);
    }

    public function deleteResponseCode(int $domainResponseCodeId)
    {
        return $this->responseCodesEndpoint->delete($domainResponseCodeId);
    }
}
