<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\String;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * Macro: selectLower
 * Purpose: Convert a string column to lowercase.
 * Example:
 *   - Given: name = "Alice"
 *   - Usage: ->selectLower('name as low')
 *   - Result: low = "alice"
 */
class Lower extends BaseMacro
{

    public static function name(): string
    {
        return 'selectLower';
    }

    public function defaultExpression($column): string
    {
        return "LOWER($column)";
    }

    public function mysql($column): string
    {
        return "LOWER($column)";
    }
}