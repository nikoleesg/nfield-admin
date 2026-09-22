<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\Quota;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

final class QuotaFrameModel extends Data
{
    /**
     * @param  array<int, QuotaFrameVariableModel>|null  $variables
     */
    public function __construct(
        public ?string $id = null,
        #[DataCollectionOf(QuotaFrameVariableModel::class)]
        public ?array $variables = null,
        public ?int $target = null,
        public int $successful = 0,
    ) {}
}
