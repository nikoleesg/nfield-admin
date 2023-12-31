<?php

namespace Nikoleesg\NfieldAdmin\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
class NewCapiInterviewerRequestData extends Data
{
    public function __construct(
        public string $user_name,
        public string $password,
        public string $client_interviewer_id,
        public string $first_name,
        public ?string $last_name,
        public ?string $email_address,
        public ?string $telephone_number,
        public bool $is_supervisor,
    ) {
    }
}
