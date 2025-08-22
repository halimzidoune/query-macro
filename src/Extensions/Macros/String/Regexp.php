<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\String;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * Macro: selectRegexp
 * Purpose: Test a regex against a string. Returns 1/0 or matched value depending on driver/options.
 * Example:
 *   - Given: email = "alice@example.com"
 *   - Usage: ->selectRegexp('email as r', '^[a-z]+@')
 *   - Result: r = 1
 */
class Regexp extends BaseMacro
{
    public static function name(): string
    {
        return 'selectRegexp';
    }

    public function defaultExpression($column, $pattern): string
    {
        return "$column REGEXP '$pattern'";
    }

    public function mysql($column, $pattern): string
    {
        return "$column REGEXP '$pattern'";
    }

    public function pgsql($column, $pattern): string
    {
        return "$column ~ '$pattern'";
    }

    public function sqlsrv($column, $pattern): string
    {
        return "$column LIKE '%[^$pattern]%'";
    }

    public function oracle($column, $pattern): string
    {
        return "REGEXP_LIKE($column, '$pattern')";
    }
}