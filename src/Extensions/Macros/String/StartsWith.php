<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\String;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * Macro: selectStartsWith
 * Purpose: Check if a string starts with a given prefix. Returns 1/0.
 * Example:
 *   - Given: name = "Alice"
 *   - Usage: ->selectStartsWith('name as sw', 'Al')
 *   - Result: sw = 1
 */
class StartsWith extends BaseMacro
{
    public static function name(): string
    {
        return 'selectStartsWith';
    }

    public function defaultExpression($column, $value): string
    {
        return "$column LIKE '$value%'";
    }

    public function mysql($column, $value): string
    {
        return "$column LIKE '$value%'";
    }

    public function pgsql($column, $value): string
    {
        return "$column LIKE '$value%'";
    }

    public function sqlsrv($column, $value): string
    {
        $escapedValue = $this->escapeForSqlServer($value);
        return "CASE WHEN $column LIKE '$escapedValue%' ESCAPE '\\' THEN 1 ELSE 0 END";
    }

    public function oracle($column, $value): string
    {
        $escapedValue = $this->escapeValue($value);
        // Use CASE statement to convert boolean to number
        return "CASE WHEN REGEXP_LIKE($column, '^$escapedValue') THEN 1 ELSE 0 END";
    }

    protected function escapeValue($value): string
    {
        return str_replace("'", "''", $value);
    }

    /**
     * Escape value for SQL Server LIKE pattern
     */
    protected function escapeForSqlServer($value): string
    {
        // Escape single quotes and SQL Server LIKE wildcards
        $value = str_replace("'", "''", $value);
        $value = str_replace('%', '\\%', $value);
        $value = str_replace('_', '\\_', $value);
        $value = str_replace('[', '\\[', $value);
        $value = str_replace(']', '\\]', $value);
        return $value;
    }
}