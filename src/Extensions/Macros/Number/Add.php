<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Number;


use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * SelectAdd - Adds two or more numbers/columns
 * Example: price + tax
 */
class Add extends BaseMacro
{
    public static function name(): string { return 'selectAdd'; }

    public function defaultExpression(...$columns): string {
        return '(' . implode(' + ', $columns) . ')';
    }
    // All databases use standard + operator
}