<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\Sample;

use Spatie\LaravelData\Data;

final class UploadSampleRecordError extends Data
{
    public function __construct(
        public ?string $errorType = null,
        public int $rowNumber = 0,
        public ?string $fieldName = null,
        public ?string $fieldValue = null,
    ) {}
}
