<?php

namespace Nikoleesg\NfieldAdmin\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
class AddressDTO extends Data
{
    public function __construct(
        public ?string $address_id,
        public string $details,
        #[WithCast(DateTimeInterfaceCast::class)]
        public ?Carbon $appointment_date,
        #[DataCollectionOf(AddressSampleDTO::class)]
        public ?DataCollection $sample_data
    ) {
    }

    public static function fromResponse(array $address): self
    {
        return new self(
            $address['AddressId'] ?? $address['address_id'] ?? null,
            $address['Details'] ?? $address['details'],
            ! is_null($address['AppointmentDate']) ? Carbon::parse($address['AppointmentDate']) : null,
            ! empty($address['SampleData']) ? AddressSampleDTO::collection($address['SampleData']) : null
        );
    }
}
