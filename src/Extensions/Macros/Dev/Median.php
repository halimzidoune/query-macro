<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Dev;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * SelectMedian - Calculates median value (50th percentile)
 * Example: PERCENTILE_CONT(0.5)
 */
class Median extends BaseMacro
{
    public static function name(): string
    {
        return 'selectMedian';
    }

    public function defaultExpression($column): string
    {
        return "PERCENTILE_CONT(0.5) WITHIN GROUP (ORDER BY $column)";
    }
}