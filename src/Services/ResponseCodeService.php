<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\ResponseCodeEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\ResponseCodeScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Domain\ResponseCodes\DomainResponseCodeResponseModel;
use Nikoleesg\NfieldAdmin\Data\Domain\ResponseCodes\DomainResponseCodeUpdateModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToResponseCode;

/**
 * One domain response code, reached through
 * `NfieldManager::responseCodes()->forResponseCode($code)`.
 *
 * Services mirror the endpoint naming: this pairs with ResponseCodeEndpoint,
 * and {@see ResponseCodeCollectionService} with ResponseCodeCollectionEndpoint.
 */
class ResponseCodeService implements ResponseCodeScopedInterface
{
    use ScopedToResponseCode;

    public function __construct(
        protected ResponseCodeEndpointInterface $responseCodeEndpoint,
    ) {}

    /**
     * Update the given fields; fields not set on the model are not sent.
     *
     * @param  array<string, mixed>|DomainResponseCodeUpdateModel  $data
     */
    public function update(array|DomainResponseCodeUpdateModel $data): DomainResponseCodeResponseModel
    {
        $payload = DomainResponseCodeUpdateModel::from($data)->toArray();

        return DomainResponseCodeResponseModel::from(
            $this->responseCodeEndpoint->update($this->getResponseCode(), $payload)
        );
    }

    public function delete(): void
    {
        $this->responseCodeEndpoint->delete($this->getResponseCode());
    }
}
