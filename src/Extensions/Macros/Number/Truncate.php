<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Number;


use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * SelectTruncate - Truncates a number to specified decimal places without rounding
 * Example: TRUNCATE(15.789, 1) → 15.7
 */
class Truncate extends BaseMacro
{
    public static function name(): string
    {
        return 'selectTruncate';
    }

    public function defaultExpression($column, int $precision = 0): string
    {
        return "TRUNC($column, $precision)";
    }

    public function mysql($column, int $precision = 0): string
    {
        return "TRUNCATE($column, $precision)";
    }

    public function pgsql($column, int $precision = 0): string
    {
        return "TRUNC($column::numeric, $precision)";
    }

    public function sqlsrv($column, int $precision = 0): string
    {
        return "ROUND($column, $precision, 1)"; // Third parameter truncates
    }

    public function oracle($column, int $precision = 0): string
    {
        return "TRUNC($column, $precision)";
    }
    public function sqlite($column, int $precision = 0): string
    {
        if ($precision === 0) {
            return "CAST($column AS INTEGER)";
        }

        return "CAST($column * (10 * $precision) AS INTEGER) / (10.0 * $precision)";
    }
}