<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * @deprecated
 */
class AddressDTO extends Data
{
    public function __construct(
        public ?string $addressId,
        public string $details,
        #[WithCast(DateTimeInterfaceCast::class)]
        public ?Carbon $appointmentDate,
        #[DataCollectionOf(AddressSampleDTO::class)]
        public ?DataCollection $sampleData
    ) {}

    public static function fromResponse(array $address): self
    {
        return new self(
            $address['addressId'] ?? null,
            $address['details'],
            ! is_null($address['appointmentDate']) ? Carbon::parse($address['appointmentDate']) : null,
            ! empty($address['sampleData']) ? AddressSampleDTO::collect($address['sampleData']) : null
        );
    }
}
