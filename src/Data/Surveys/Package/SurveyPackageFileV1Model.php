<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\Package;

use Spatie\LaravelData\Data;

/**
 * A file contained in a published package, described by name, MD5 and size in bytes.
 *
 * Mirrors NfieldPublicApi.Models.Surveys.SurveyPackageFileV1Model.
 */
final class SurveyPackageFileV1Model extends Data
{
    public function __construct(
        public ?string $fileName = null,
        public ?string $md5 = null,
        public int $size = 0,
    ) {}
}
