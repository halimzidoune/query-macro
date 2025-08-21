<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Dev;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * SelectLog - Calculates logarithm with specified base
 * Example: LOG(100, 10) → 2
 */
class Log extends BaseMacro
{
    public static function name(): string
    {
        return 'selectLog';
    }

    public function defaultExpression($column, $base = 10): string
    {
        return "LOG($base, $column)";
    }
}