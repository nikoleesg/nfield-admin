<?php


namespace Nikoleesg\NfieldAdmin\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\CamelCaseMapper;

#[MapInputName(CamelCaseMapper::class)]
class ResponseCodeDTO extends Data
{
    public function __construct(
        #[MapName('response_code')]
        public int $id,
        public string $description,
        #[MapName('relocation_url')]
        public ?string $url,
        public ?bool $is_definite,
        public ?bool $is_selectable,
        public ?bool $allow_appointment,
        public bool $channel_capi,
        public bool $channel_cati,
        public bool $channel_online
    ) {
    }

}
