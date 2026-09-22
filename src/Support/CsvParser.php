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
     * @return array Parsed data with headers as keys
     *
     * @throws RuntimeException If CSV structure is invalid
     */
    public static function parse(string $csvData): array
    {
        // Handle encoding conversion
        $csvData = self::normalizeEncoding($csvData);

        // Split into lines
        $lines = self::splitIntoLines($csvData);

        if (empty($lines)) {
            return [];
        }

        // Extract and validate header
        $header = str_getcsv(array_shift($lines), "\t");

        if (empty($header[0])) {
            throw new RuntimeException('CSV file has no header row');
        }

        // Parse data rows
        return self::parseDataRows($lines, $header);
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

    /**
     * Split CSV data into lines, handling different line endings
     */
    protected static function splitIntoLines(string $data): array
    {
        // Split by any combination of line endings (Windows, Unix, Mac)
        $lines = preg_split('/\r\n|\n|\r/', $data);

        // Remove empty lines
        return array_values(array_filter($lines, fn ($line) => trim($line) !== ''));
    }

    /**
     * Parse data rows into associative arrays using headers as keys
     */
    protected static function parseDataRows(array $lines, array $header): array
    {
        $headerCount = count($header);
        $results = [];

        foreach ($lines as $lineNumber => $line) {
            // Skip empty lines
            if (trim($line) === '') {
                continue;
            }

            // Parse the line
            $row = str_getcsv($line, "\t");

            // Validate column count
            if (count($row) !== $headerCount) {
                // Log warning or handle mismatch
                Log::warning("CSV row {$lineNumber} has mismatched columns", [
                    'expected' => $headerCount,
                    'actual' => count($row),
                    'line' => $line,
                ]);

                continue; // Skip malformed rows
            }

            // Combine header with row data
            $results[] = array_combine($header, $row);
        }

        return $results;
    }
}
