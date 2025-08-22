<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Casts;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * Macro: selectString
 * Purpose: Cast a value to string.
 * Example:
 *   - Given: count = 5
 *   - Usage: ->selectString('count as s')
 *   - Result: s = "5"
 */
class SelectString extends BaseMacro
{
    public static function name(): string
    {
        return 'selectString';
    }

    public function defaultExpression($column, $length = null): string
    {
        $length = $length ? "($length)" : "";
        return "CAST($column AS VARCHAR$length)";
    }

    public function mysql($column, $length = null): string
    {
        return $length ? "CAST($column AS CHAR($length))" : "CAST($column AS CHAR)";
    }

    public function pgsql($column, $length = null): string
    {
        return $length ? "CAST($column AS VARCHAR($length))" : "CAST($column AS TEXT)";
    }

    public function sqlsrv($column, $length = null): string
    {
        return $length ? "CAST($column AS NVARCHAR($length))" : "CAST($column AS NVARCHAR(MAX))";
    }

    public function oracle($column, $length = null): string
    {
        return $length ? "CAST($column AS VARCHAR2($length))" : "CAST($column AS VARCHAR2(4000))";
    }

    public function sqlite($column, $length = null): string
    {
        return "CAST($column AS TEXT)";
    }

}