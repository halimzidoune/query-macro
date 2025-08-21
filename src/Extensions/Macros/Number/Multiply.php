<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Number;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * SelectMultiply - Multiplies two or more numbers/columns
 * Example: quantity * unit_price
 */
class Multiply extends BaseMacro
{
    public static function name(): string
    {
        return 'selectMultiply';
    }

    public function defaultExpression(...$columns): string
    {
        return '(' . implode(' * ', $columns) . ')';
    }
}