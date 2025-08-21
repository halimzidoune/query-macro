<?php

namespace Hz\QueryMacroHelper\Extensions;

class TimeUnit
{
    public const MICRO_SECONDS = "microsecond";
    public const SECONDS = "second";
    public const MINUTE = "minute";

    public const HOUR = "hour";
    public const DAY = "day";
    public const WEEK = "week";

    public const MONTH = "month";
    public const YEAR = "year";

    public const QUARTER = "quarter";


    /**
     * Normalize interval units to standard plural form
     */
    public static function normalizeInterval(string $interval): string
    {
        $intervals = [
            'microsecond' => 'microseconds',
            'second' => 'seconds',
            'minute' => 'minutes',
            'hour' => 'hours',
            'day' => 'days',
            'week' => 'weeks',
            'month' => 'months',
            'quarter' => 'months', // 3 months
            'year' => 'years',
        ];

        $lower = strtolower($interval);
        return $intervals[$lower] ?? $interval;
    }

    /**
     * Convert to MySQL interval units
     */
    public static function convertToMySQLUnit(string $interval): string
    {
        $units = [
            'microsecond' => 'MICROSECOND',
            'second' => 'SECOND',
            'minute' => 'MINUTE',
            'hour' => 'HOUR',
            'day' => 'DAY',
            'week' => 'WEEK',
            'month' => 'MONTH',
            'quarter' => 'QUARTER',
            'year' => 'YEAR',
        ];

        $lower = strtolower($interval);
        return $units[$lower] ?? strtoupper($interval);
    }


    /**
     * Convert to SQL Server datepart units
     */
    public static function convertToSQLServerUnit(string $interval): string
    {
        $units = [
            'microsecond' => 'MICROSECOND',
            'second' => 'SECOND',
            'minute' => 'MINUTE',
            'hour' => 'HOUR',
            'day' => 'DAY',
            'week' => 'WEEK',
            'month' => 'MONTH',
            'quarter' => 'QUARTER',
            'year' => 'YEAR',
        ];

        $lower = strtolower($interval);
        return $units[$lower] ?? strtoupper($interval);
    }

    /**
     * Convert to Oracle interval units
     */
    public static function convertToOracleUnit(string $interval): string
    {
        $units = [
            'microsecond' => 'SECOND', // Oracle doesn't support microsecond directly
            'second' => 'SECOND',
            'minute' => 'MINUTE',
            'hour' => 'HOUR',
            'day' => 'DAY',
            'week' => 'DAY', // 7 days
            'month' => 'MONTH',
            'quarter' => 'MONTH', // 3 months
            'year' => 'YEAR',
        ];

        $lower = strtolower($interval);
        return $units[$lower] ?? strtoupper($interval);
    }

    /**
     * Convert to SQLite datetime modifiers
     */
    public static function convertToSQLiteModifier(string $interval): string
    {
        $modifiers = [
            'microsecond' => 'microseconds',
            'second' => 'seconds',
            'minute' => 'minutes',
            'hour' => 'hours',
            'day' => 'days',
            'week' => 'days', // 7 days
            'month' => 'months',
            'quarter' => 'months', // 3 months
            'year' => 'years',
        ];

        $lower = strtolower($interval);
        return $modifiers[$lower] ?? $interval;
    }

}
