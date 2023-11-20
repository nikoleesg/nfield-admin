<?php

namespace Nikoleesg\NfieldAdmin\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Carbon\Carbon;

#[MapInputName(StudlyCaseMapper::class)]
class AddressData extends Data
{
    public function __construct(
        public ?string $address_id,
        public string $details,
        #[WithCast(DateTimeInterfaceCast::class)]
        public ?Carbon $appointment_date,
        #[DataCollectionOf(AddressSampleData::class)]
        public ?DataCollection $sample_data
    ) {
    }

    public static function fromResponse(array $address): self
    {
        return new self(
            $address['AddressId'] ?? $address['address_id'] ?? null,
            $address['Details'] ?? $address['details'],
            !is_null($address['AppointmentDate']) ? Carbon::parse($address['AppointmentDate']) : null,
            !empty($address['SampleData']) ? AddressSampleData::collection($address['SampleData']) : null
        );
    }
}
