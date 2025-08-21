<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Datetime;


use Hz\QueryMacroHelper\Extensions\BaseMacro;

class FormatDate extends BaseMacro
{
    public static function name(): string
    {
        return 'selectDateFormat';
    }

    public function defaultExpression($column, string $format): string
    {
        return $this->pgsql($column, $format); // Use PostgreSQL as default
    }

    public function mysql($column, string $format): string
    {
        $dbFormat = $this->convertToMySQLFormat($format);
        return "DATE_FORMAT($column, '$dbFormat')";
    }

    public function pgsql($column, string $format): string
    {
        $dbFormat = $this->convertToPostgresFormat($format);
        return "TO_CHAR($column, '$dbFormat')";
    }

    public function sqlsrv($column, string $format): string
    {
        $dbFormat = $this->convertToSQLServerFormat($format);
        return "FORMAT($column, '$dbFormat')";
    }

    public function oracle($column, string $format): string
    {
        $dbFormat = $this->convertToOracleFormat($format);
        return "TO_CHAR($column, '$dbFormat')";
    }

    public function sqlite($column, string $format): string
    {
        $dbFormat = $this->convertToSQLiteFormat($format);
        return "strftime('$dbFormat', $column)";
    }

    /**
     * Convert Carbon format to MySQL DATE_FORMAT specifiers
     */
    protected function convertToMySQLFormat(string $carbonFormat): string
    {
        $map = [
            // Day
            'd' => '%d',    // Day of the month, 2 digits with leading zeros
            'D' => '%a',    // A textual representation of a day, three letters
            'j' => '%e',    // Day of the month without leading zeros
            'l' => '%W',    // A full textual representation of the day of the week
            // Month
            'F' => '%M',    // A full textual representation of a month
            'm' => '%m',    // Numeric representation of a month, with leading zeros
            'M' => '%b',    // A short textual representation of a month, three letters
            'n' => '%c',    // Numeric representation of a month, without leading zeros
            // Year
            'Y' => '%Y',    // A full numeric representation of a year, 4 digits
            'y' => '%y',    // A two digit representation of a year
            // Time
            'a' => '%p',    // Lowercase Ante meridiem and Post meridiem
            'A' => '%p',    // Uppercase Ante meridiem and Post meridiem
            'g' => '%h',    // 12-hour format of an hour without leading zeros
            'G' => '%H',    // 24-hour format of an hour without leading zeros
            'h' => '%h',    // 12-hour format of an hour with leading zeros
            'H' => '%H',    // 24-hour format of an hour with leading zeros
            'i' => '%i',    // Minutes with leading zeros
            's' => '%s',    // Seconds with leading zeros
            // Other
            'u' => '%f',    // Microseconds
        ];

        return strtr($carbonFormat, $map);
    }

    /**
     * Convert Carbon format to PostgreSQL TO_CHAR specifiers
     */
    protected function convertToPostgresFormat(string $carbonFormat): string
    {
        $map = [
            // Day
            'd' => 'DD',    // Day of the month, 2 digits with leading zeros
            'D' => 'Dy',     // Abbreviated uppercase day name
            'j' => 'DD',     // Day of the month without leading zeros (same as DD in Postgres)
            'l' => 'Day',    // Full uppercase day name
            // Month
            'F' => 'Month',  // Full uppercase month name
            'm' => 'MM',     // Month number with leading zeros
            'M' => 'Mon',    // Abbreviated uppercase month name
            'n' => 'MM',     // Month number without leading zeros (same as MM in Postgres)
            // Year
            'Y' => 'YYYY',  // 4-digit year
            'y' => 'YY',     // 2-digit year
            // Time
            'a' => 'AM',    // AM/PM
            'A' => 'AM',     // AM/PM (uppercase)
            'g' => 'HH12',   // 12-hour format without leading zeros
            'G' => 'HH24',  // 24-hour format without leading zeros
            'h' => 'HH12',   // 12-hour format with leading zeros
            'H' => 'HH24',  // 24-hour format with leading zeros
            'i' => 'MI',     // Minutes
            's' => 'SS',     // Seconds
            // Other
            'u' => 'US',     // Microseconds
        ];

        return strtr($carbonFormat, $map);
    }

    /**
     * Convert Carbon format to SQL Server FORMAT specifiers
     */
    protected function convertToSQLServerFormat(string $carbonFormat): string
    {
        $map = [
            // Day
            'd' => 'dd',     // Day of the month, 2 digits with leading zeros
            'D' => 'ddd',    // Abbreviated day name
            'j' => 'd',     // Day of the month without leading zeros
            'l' => 'dddd',  // Full day name
            // Month
            'F' => 'MMMM',   // Full month name
            'm' => 'MM',     // Month number with leading zeros
            'M' => 'MMM',    // Abbreviated month name
            'n' => 'M',      // Month number without leading zeros
            // Year
            'Y' => 'yyyy',  // 4-digit year
            'y' => 'yy',     // 2-digit year
            // Time
            'a' => 'tt',     // AM/PM
            'A' => 'tt',     // AM/PM (uppercase)
            'g' => 'h',      // 12-hour format without leading zeros
            'G' => 'H',      // 24-hour format without leading zeros
            'h' => 'hh',     // 12-hour format with leading zeros
            'H' => 'HH',     // 24-hour format with leading zeros
            'i' => 'mm',     // Minutes
            's' => 'ss',     // Seconds
            // Other
            'u' => 'ffffff', // Microseconds
        ];

        return strtr($carbonFormat, $map);
    }

    /**
     * Convert Carbon format to Oracle TO_CHAR specifiers
     */
    protected function convertToOracleFormat(string $carbonFormat): string
    {
        // Oracle format is very similar to PostgreSQL
        return $this->convertToPostgresFormat($carbonFormat);
    }

    /**
     * Convert Carbon format to SQLite strftime specifiers
     */
    protected function convertToSQLiteFormat(string $carbonFormat): string
    {
        $map = [
            // Day
            'd' => '%d',     // Day of the month, 2 digits with leading zeros
            'D' => '',       // Not directly supported (would need custom handling)
            'j' => '%d',     // Day of the month without leading zeros (same in SQLite)
            'l' => '',      // Not directly supported
            // Month
            'F' => '',       // Not directly supported
            'm' => '%m',     // Month number with leading zeros
            'M' => '',       // Not directly supported
            'n' => '%m',     // Month number without leading zeros (same in SQLite)
            // Year
            'Y' => '%Y',     // 4-digit year
            'y' => '%y',     // 2-digit year
            // Time
            'a' => '%p',     // AM/PM
            'A' => '%p',     // AM/PM (uppercase)
            'g' => '%I',     // 12-hour format without leading zeros
            'G' => '%H',    // 24-hour format without leading zeros
            'h' => '%I',    // 12-hour format with leading zeros
            'H' => '%H',     // 24-hour format with leading zeros
            'i' => '%M',    // Minutes
            's' => '%S',     // Seconds
            // Other
            'u' => '',       // Not supported
        ];

        return strtr($carbonFormat, $map);
    }
}