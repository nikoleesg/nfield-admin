<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\Sample;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

final class SampleUploadStatus extends Data
{
    /**
     * @param  array<int, UploadSampleRecordError>|null  $errorMessages
     */
    public function __construct(
        public ?string $processingStatus = null,
        public int $totalRecordCount = 0,
        public int $insertedCount = 0,
        public int $updatedCount = 0,
        public int $duplicateKeyCount = 0,
        public int $emptyKeyCount = 0,
        public int $invalidKeyCount = 0,
        public int $invalidDataCount = 0,
        public int $skippedCount = 0,
        public bool $headerInvalid = false,
        public int $headerInvalidColumnsCount = 0,
        public bool $headerDataMismatch = false,
        #[DataCollectionOf(UploadSampleRecordError::class)]
        public ?array $errorMessages = null,
    ) {}
}
