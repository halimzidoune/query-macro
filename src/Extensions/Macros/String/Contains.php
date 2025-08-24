<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\String;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * Macro: selectContains
 * Purpose: Check if a string contains a given substring. Returns 1/0.
 * Example:
 *   - Given: designation = "Manager"
 *   - Usage: ->selectContains('designation as c', 'nag')
 *   - Result: c = 1
 */
class Contains extends BaseMacro
{
    public static function name(): string
    {
        return 'selectContains';
    }

    public function defaultExpression($column, $value): string
    {
        return "$column LIKE '%$value%'";
    }

    public function mysql($column, $value): string
    {
        return "LOCATE('$value', $column) > 0";
    }

    public function pgsql($column, $value): string
    {
        return "POSITION('$value' IN $column) > 0";
    }

    public function sqlsrv($column, $value): string
    {
        $escapedValue = $this->escapeValue($value);
        return "CASE WHEN CHARINDEX('$escapedValue', $column) > 0 THEN 1 ELSE 0 END";
    }

    public function oracle($column, $value): string
    {
        $escapedValue = $this->escapeValue($value);
        return "CASE WHEN INSTR(UPPER($column), UPPER('$escapedValue')) > 0 THEN 1 ELSE 0 END";
    }
    /**
     * Escape value to prevent SQL injection
     */
    protected function escapeValue($value): string
    {
        return str_replace("'", "''", $value);
    }

}