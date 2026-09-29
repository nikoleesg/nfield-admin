<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\Package;

use Spatie\LaravelData\Data;

/**
 * A relocation (reason or response code, and its URL) in a published package.
 *
 * Mirrors NfieldPublicApi.Models.Surveys.SurveyPackageRelocationV1Model.
 */
final class SurveyPackageRelocationV1Model extends Data
{
    public function __construct(
        public ?string $reason = null,
        public ?string $url = null,
    ) {}
}
