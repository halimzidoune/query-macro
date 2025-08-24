<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\String;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * Macro: selectTrim
 * Purpose: Trim leading and trailing whitespace.
 * Example:
 *   - Given: designation = "  Chief  "
 *   - Usage: ->selectTrim('designation as t')
 *   - Result: t = "Chief"
 */
class Trim extends BaseMacro
{
    public static function name(): string
    {
        return 'selectTrim';
    }

    public function defaultExpression($column): string
    {
        return "TRIM($column)";
    }

    public function sqlsrv($column): string
    {
        // SQL Server 2017+ supports TRIM(), but for compatibility with older versions:
        return "LTRIM(RTRIM($column))";
    }
}