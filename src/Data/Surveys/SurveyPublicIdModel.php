<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Nikoleesg\NfieldAdmin\Enums\SurveyPublicIdLinkTypeEnum;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
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
