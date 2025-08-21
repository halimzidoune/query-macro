<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Dev;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * SelectCoalesce - Returns first non-null value
 * Example: COALESCE(discount, 0)
 */
class Cos extends BaseMacro
{
    public static function name(): string
    {
        return 'selectCos';
    }

    public function defaultExpression(...$columns): string
    {
        return "COALESCE(" . implode(', ', $columns) . ")";
    }
}