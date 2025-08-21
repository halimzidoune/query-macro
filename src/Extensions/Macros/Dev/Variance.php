<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Dev;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * SelectVariance - Calculates statistical variance
 * Example: VARIANCE(price)
 */
class Variance extends BaseMacro
{
    public static function name(): string
    {
        return 'selectVariance';
    }

    public function defaultExpression($column): string
    {
        return "VARIANCE($column)";
    }
}