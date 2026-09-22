<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Nikoleesg\NfieldAdmin\Enums\SurveyPublicIdLinkTypeEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

class SurveyPublicIdModel extends Data
{
    public function __construct(
        public ?string $id,
        #[WithCast(EnumCast::class)]
        public ?SurveyPublicIdLinkTypeEnum $linkType,
        public ?string $url,
        public bool $active = false,
    ) {}
}
