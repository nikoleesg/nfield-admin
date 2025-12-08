<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\Addresses;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
#[MapOutputName(StudlyCaseMapper::class)]
class AddressModel extends Data
{
    public function __construct(
        public ?string $addressId,
        public string $details,
        #[WithCast(DateTimeInterfaceCast::class)]
        public ?Carbon $appointmentDate,
        /** @var Collection<int, AddressSampleDataModel> */
        public ?Collection $sampleData
    ) {
    }
}
