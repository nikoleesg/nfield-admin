<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Templates;

use Spatie\LaravelData\Data;

/**
 * Where to download a theme from.
 *
 * Mirrors NfieldPublicApi.Models.Templates.ThemeUrlResponseModel.
 */
final class ThemeUrlResponseModel extends Data
{
    public function __construct(
        public ?string $url = null,
    ) {}
}
