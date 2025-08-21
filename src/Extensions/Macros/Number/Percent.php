<?php


namespace Hz\QueryMacroHelper\Extensions\Macros\Number;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * PERCENT Macro - Calculates percentages with database-specific optimizations
 *
 * Provides cross-database percentage calculation:
 * - Handles division by zero safely
 * - Allows customizable decimal precision
 * - Works with columns or literal values
 */
class Percent extends BaseMacro
{
    public static function name(): string
    {
        return 'selectPercent';
    }

    public function defaultExpression($numerator, $denominator, int $precision = 2): string
    {
        return "ROUND(100.0 * $numerator / NULLIF($denominator, 0), $precision)";
    }

    public function mysql($numerator, $denominator, int $precision = 2): string
    {
        return $this->defaultExpression($numerator, $denominator, $precision);
    }

    public function pgsql($numerator, $denominator, int $precision = 2): string
    {
        return "ROUND(100.0 * $numerator / NULLIF($denominator, 0)::numeric, $precision)";
    }

    public function sqlsrv($numerator, $denominator, int $precision = 2): string
    {
        return "ROUND(100.0 * $numerator / NULLIF($denominator, 0), $precision, 1)";
    }

    public function oracle($numerator, $denominator, int $precision = 2): string
    {
        return "ROUND(100.0 * $numerator / NULLIF($denominator, 0), $precision)";
    }

    public function sqlite($numerator, $denominator, int $precision = 2): string
    {
        return "ROUND(100.0 * $numerator / (CASE WHEN $denominator = 0 THEN NULL ELSE $denominator END), $precision)";
    }

}