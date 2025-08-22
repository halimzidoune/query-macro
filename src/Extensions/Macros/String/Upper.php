<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\String;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * Macro: selectUpper
 * Purpose: Convert a string column to uppercase.
 * Example:
 *   - Given: name = "Alice"
 *   - Usage: ->selectUpper('name as up')
 *   - Result: up = "ALICE"
 */
class Upper extends BaseMacro
{
    public static function name(): string
    {
        return 'selectUpper';
    }

    public function defaultExpression($column): string
    {
        return "UPPER($column)";
    }
}