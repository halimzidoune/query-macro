<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\String;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * Macro: selectCase
 * Purpose: Map specific input values to outputs via SQL CASE, with optional default.
 * Example:
 *   - Given: designation = "Manager"
 *   - Usage: ->selectCase('designation as label', ['Manager' => 'MGR', 'Dev' => 'DEV'], 'OTHER')
 *   - Result: label = "MGR"
 */
class SelectCase extends BaseMacro
{
    public static function name(): string
    {
        return 'selectCase';
    }

    public function defaultExpression(string $column, array $cases, ?string $else = null): string
    {
        $sql = "CASE $column";

        foreach ($cases as $when => $then) {
            $sql .= " WHEN " . $this->quoteValue($when) . " THEN " . $this->quoteValue($then);
        }

        if ($else !== null) {
            $sql .= " ELSE " . $this->quoteValue($else);
        }
        return $sql . " END";
    }

    public function mysql(string $column, array $cases, ?string $else = null): string
    {
        return $this->defaultExpression($column, $cases, $else);
    }

    public function sqlsrv(string $column, array $cases, ?string $else = null): string
    {
        return $this->defaultExpression($column, $cases, $else);
    }

    protected function quoteValue($value): string
    {
        if(is_bool($value)){
            return (int)$value;
        }
        if (is_numeric($value) && !is_string($value)) {
            return (string)$value;
        }

        return "'" . addslashes((string)$value) . "'";
    }

    public function oracle(string $column, array $cases, ?string $else = null): string
    {
        $sql = "CASE ";

        foreach ($cases as $when => $then) {
            // For each WHEN clause, explicitly convert both sides to the same type
            $sql .= "WHEN $column = " . $this->oracleQuoteValue($when) .
                " THEN " . $this->oracleQuoteValue($then) . " ";
        }

        if ($else !== null) {
            $sql .= "ELSE " . $this->oracleQuoteValue($else) . " ";
        }
        return $sql . "END";
    }

    protected function oracleQuoteValue($value): string
    {
        if (is_bool($value)) {
            return $value ? '1' : '0';
        }
        if (is_numeric($value) && !is_string($value)) {
            return (string)$value;
        }
        if ($value === null) {
            return 'NULL';
        }

        // For string values, ensure they're properly quoted
        return "'" . str_replace("'", "''", (string)$value) . "'";
    }

    public function pgsql(string $column, array $cases, ?string $else = null): string
    {
        $sql = "CASE ";

        foreach ($cases as $when => $then) {
            $sql .= "WHEN $column = " . $this->pgsqlQuoteValue($when) . " THEN " . $this->pgsqlQuoteValue($then) . " ";
        }

        if ($else !== null) {
            $sql .= "ELSE " . $this->pgsqlQuoteValue($else) . " ";
        }
        return $sql . "END";
    }

    protected function pgsqlQuoteValue($value): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        if (is_numeric($value) && !is_string($value)) {
            return (string)$value;
        }
        if ($value === null) {
            return 'NULL';
        }

        return "'" . str_replace("'", "''", (string)$value) . "'";
    }
}