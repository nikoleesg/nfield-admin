<?php


namespace Nikoleesg\NfieldAdmin\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Carbon\Carbon;

#[MapInputName(StudlyCaseMapper::class)]
class QuotaFrameVersionData extends Data
{
    public function __construct(
        public string $id,
        public string $e_tag,
        #[WithCast(DateTimeInterfaceCast::class)]
        public ?Carbon $published_date,
    ) {
    }

    public static function fromResponse(array $data): self
    {
        return new self(
            $data['Id'],
            $data['ETag'],
            !is_null($data['PublishedDate']) ? Carbon::parse($data['PublishedDate']) : null
        );
    }

}
