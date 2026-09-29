<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Requests;

use Nikoleesg\NfieldAdmin\Enums\RequestHttpMethodEnum;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

/**
 * A configuration for the scripting *REQUEST command: the URI to call, how,
 * and with which payload and headers.
 *
 * Mirrors NfieldPublicApi.Models.Requests.RequestModel (/v2/requests), named
 * "request configuration" as the spec describes it, so it is not mistaken
 * for an HTTP request. The PUT response is Nfield.Manager.Domains.Models.Request,
 * the same fields plus a read-only `timeout`, which is never sent back.
 */
final class RequestConfigurationModel extends Data
{
    /**
     * @param  list<RequestConfigurationHeaderModel>|null  $headers
     */
    public function __construct(
        public string $name,
        public string $uri,
        public ?string $description = null,
        public ?string $payloadTemplate = null,
        #[WithCast(EnumCast::class, type: RequestHttpMethodEnum::class)]
        public RequestHttpMethodEnum|Optional $requestHttpMethod = new Optional,
        public ?string $helpUri = null,
        #[DataCollectionOf(RequestConfigurationHeaderModel::class)]
        public ?array $headers = null,
        public int|Optional $id = new Optional,
        #[Hidden]
        public int|Optional $timeout = new Optional,
    ) {}
}
