<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\String;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * Macro: selectEndsWith
 * Purpose: Check if a string ends with a given suffix. Returns 1/0.
 * Example:
 *   - Given: designation = "Dev"
 *   - Usage: ->selectEndsWith('designation as ew', 'ev')
 *   - Result: ew = 1
 */
class EndsWith extends BaseMacro
{
    public static function name(): string
    {
        return 'selectEndsWith';
    }
    public function defaultExpression($column, $value): string
    {
        return "$column LIKE '%$value'";
    }

    public function mysql($column, $value): string
    {
        return "RIGHT($column, " . strlen($value) . ") = '$value'";
    }

    public function pgsql($column, $value): string
    {
        return "$column LIKE '%$value'";
    }

    public function sqlsrv($column, $value): string
    {
        $escapedValue = $this->escapeForSqlServer($value);
        return "CASE WHEN $column LIKE '%$escapedValue' ESCAPE '\\' THEN 1 ELSE 0 END";
    }



    public function sqlite($column, $value): string
    {
        return "$column GLOB '*$value'";
    }

    public function oracle($column, $value): string
    {
        $escapedValue = $this->escapeValue($value);
        $length = strlen($value);

        return "CASE 
            WHEN $column IS NULL THEN 0
            WHEN UPPER(SUBSTR($column, -$length)) = UPPER('$escapedValue') THEN 1 
            ELSE 0 
        END";
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