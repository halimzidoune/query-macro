<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\String;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * Macro: selectLength
 * Purpose: Get length of a string column.
 * Example:
 *   - Given: name = "Alice"
 *   - Usage: ->selectLength('name as len')
 *   - Result: len = 5
 */
class Length extends BaseMacro
{

    public static function name(): string
    {
        return 'selectLength';
    }

    public function defaultExpression($column): string
    {
        return "LENGTH($column)";
    }

    public function mysql($column): string
    {
        return "CHAR_LENGTH($column)";
    }

    public function pgsql($column): string
    {
        return "CHAR_LENGTH($column)";
    }

    public function sqlsrv($column): string
    {
        return "LEN($column)";
    }


    public function oracle($column): string
    {
        return "CAST(LENGTH({$column}) AS INTEGER)";
    }
}