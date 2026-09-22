<?php

declare(strict_types=1);

use Nikoleesg\NfieldAdmin\Support\CsvParser;

it('parses UTF-8 with BOM correctly', function () {
    $bom = "\xEF\xBB\xBF";
    $csv = $bom."Header1\tHeader2\nValue1\tValue2";

    $result = CsvParser::parse($csv);

    expect($result)->toHaveCount(1)
        ->and($result[0]['Header1'])->toBe('Value1')
        ->and($result[0]['Header2'])->toBe('Value2');
});

it('parses UTF-16LE with BOM correctly', function () {
    $bom = "\xFF\xFE";
    $csv = mb_convert_encoding("Header1\tHeader2\nValue1\tValue2", 'UTF-16LE', 'UTF-8');
    $csv = $bom.$csv;

    $result = CsvParser::parse($csv);

    expect($result)->toHaveCount(1)
        ->and($result[0]['Header1'])->toBe('Value1')
        ->and($result[0]['Header2'])->toBe('Value2');
});

it('parses standard UTF-8 without BOM correctly', function () {
    $csv = "Header1\tHeader2\nValue1\tValue2";

    $result = CsvParser::parse($csv);

    expect($result)->toHaveCount(1)
        ->and($result[0]['Header1'])->toBe('Value1')
        ->and($result[0]['Header2'])->toBe('Value2');
});

it('returns empty array for empty input', function () {
    expect(CsvParser::parse(''))->toBeEmpty();
});

it('throws exception if no header row', function () {
    CsvParser::parse("\tHeader2\nValue1\tValue2");
})->throws(RuntimeException::class, 'CSV file has no header row');
