<?php

declare(strict_types=1);

use Nikoleesg\NfieldAdmin\Enums\SurveyTypeEnum;

/**
 * #29: every enum, every case.
 *
 * `SurveyTypeEnum::channel()` is the only helper method in `src/Enums/`, and
 * `SurveyTypeEnumTest` covers it case by case. What the rest need pinning for
 * is their backing values: a DTO casts to and from them, so a renumbered case
 * silently changes what goes on the wire.
 */

/**
 * @return array<string, array{0: class-string<BackedEnum>}>
 */
function enumClasses(): array
{
    $cases = [];

    foreach (glob(__DIR__.'/../../src/Enums/*.php') as $file) {
        $name = basename($file, '.php');

        $cases[$name] = ['Nikoleesg\NfieldAdmin\Enums\\'.$name];
    }

    return $cases;
}

it('round-trips every case through its backing value', function (string $enum) {
    $cases = $enum::cases();

    expect($cases)->not->toBeEmpty();

    foreach ($cases as $case) {
        expect($enum::from($case->value))->toBe($case)
            ->and($enum::tryFrom($case->value))->toBe($case);
    }
})->with(enumClasses());

it('declares no duplicate backing values', function (string $enum) {
    // Two cases sharing a value make `from()` lossy: the second is
    // unreachable. `SurveyTypeEnum` has two such cases commented out for
    // exactly this reason.
    $values = array_map(fn (BackedEnum $case): string|int => $case->value, $enum::cases());

    expect($values)->toBe(array_values(array_unique($values)));
})->with(enumClasses());

it('returns null rather than throwing on an unknown value', function (string $enum) {
    $unknown = is_int($enum::cases()[0]->value) ? PHP_INT_MAX : '__not_a_case__';

    expect($enum::tryFrom($unknown))->toBeNull();
})->with(enumClasses());

it('maps every survey type onto a channel', function () {
    // The helper is exhaustive by construction — a `match` with no default —
    // so a new case without a channel is a runtime error, not a fallthrough.
    foreach (SurveyTypeEnum::cases() as $case) {
        expect($case->channel())->toBeIn(['Online', 'CAPI']);
    }
});
