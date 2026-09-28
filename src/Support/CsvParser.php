<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Support;

use Illuminate\Support\Facades\Log;
use RuntimeException;

class CsvParser
{
    /**
     * Parse CSV string data into an associative array
     *
     * @param  string  $csvData  Raw CSV data (potentially UTF-16LE with BOM or UTF-8)
     * @return array<int, array<string, string>> Parsed data with headers as keys
     *
     * @throws RuntimeException If CSV structure is invalid
     */
    public static function parse(string $csvData): array
    {
        // Handle encoding conversion
        $csvData = self::normalizeEncoding($csvData);

        if (trim($csvData) === '') {
            return [];
        }

        $stream = fopen('php://temp', 'r+');
        if ($stream === false) {
            throw new RuntimeException('Failed to create temporary stream for CSV parsing');
        }

        try {
            fwrite($stream, $csvData);
            rewind($stream);

            $header = null;
            $rowNumber = 0;

            while (($row = fgetcsv($stream, 0, "\t")) !== false) {
                $rowNumber++;

                if (self::isEmptyRow($row)) {
                    continue;
                }

                $header = $row;
                break;
            }

            if ($header === null || empty($header[0])) {
                throw new RuntimeException('CSV file has no header row');
            }

            $headerCount = count($header);
            $results = [];

            while (($row = fgetcsv($stream, 0, "\t")) !== false) {
                $rowNumber++;

                if (self::isEmptyRow($row)) {
                    continue;
                }

                $actualCount = count($row);
                if ($actualCount !== $headerCount) {
                    Log::warning("CSV row {$rowNumber} has mismatched columns", [
                        'expected' => $headerCount,
                        'actual' => $actualCount,
                    ]);

                    throw new RuntimeException("CSV row {$rowNumber} has mismatched columns: expected {$headerCount}, got {$actualCount}");
                }

                $results[] = array_combine($header, $row);
            }

            return $results;
        } finally {
            fclose($stream);
        }
    }

    /**
     * Determine if a parsed CSV row is empty.
     *
     * @param  array<int, string|null>  $row
     */
    protected static function isEmptyRow(array $row): bool
    {
        if ($row === [null]) {
            return true;
        }

        return count($row) === 1 && trim((string) $row[0]) === '';
    }

    /**
     * Normalize CSV encoding to UTF-8 and remove BOM
     */
    protected static function normalizeEncoding(string $data): string
    {
        // Detect UTF-16LE BOM
        if (str_starts_with($data, "\xFF\xFE")) {
            // Remove the BOM and convert from UTF-16LE to UTF-8
            $data = substr($data, 2);
            $data = mb_convert_encoding($data, 'UTF-8', 'UTF-16LE');
        } elseif (str_starts_with($data, "\xFE\xFF")) {
            // UTF-16BE BOM just in case
            $data = substr($data, 2);
            $data = mb_convert_encoding($data, 'UTF-8', 'UTF-16BE');
        } elseif (str_starts_with($data, "\xEF\xBB\xBF")) {
            // UTF-8 BOM
            $data = substr($data, 3);
        }

        return $data;
    }
}
