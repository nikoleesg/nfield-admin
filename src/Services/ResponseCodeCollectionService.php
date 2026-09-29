<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\ResponseCodeCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Domain\ResponseCodes\DomainResponseCodeCreateModel;
use Nikoleesg\NfieldAdmin\Data\Domain\ResponseCodes\DomainResponseCodeResponseModel;

/**
 * The domain (tenant-wide) response codes, reached through `NfieldManager::responseCodes()`.
 *
 * Services mirror the endpoint naming: this pairs with
 * ResponseCodeCollectionEndpoint, and {@see ResponseCodeService} with
 * ResponseCodeEndpoint.
 */
class ResponseCodeCollectionService
{
    public function __construct(
        protected ResponseCodeCollectionEndpointInterface $responseCodeCollectionEndpoint,
    ) {}

    /** @return Collection<int, DomainResponseCodeResponseModel> */
    public function list(): Collection
    {
        return DomainResponseCodeResponseModel::collect($this->responseCodeCollectionEndpoint->list(), Collection::class);
    }

    /**
     * @param  array<string, mixed>|DomainResponseCodeCreateModel  $data
     */
    public function create(array|DomainResponseCodeCreateModel $data): DomainResponseCodeResponseModel
    {
        $payload = DomainResponseCodeCreateModel::from($data)->toArray();

        return DomainResponseCodeResponseModel::from($this->responseCodeCollectionEndpoint->create($payload));
    }

    /**
     * One domain response code, by its code (e.g. 210).
     */
    public function forResponseCode(int $responseCode): ResponseCodeService
    {
        return app(ResponseCodeService::class)->setResponseCode($responseCode);
    }
}
