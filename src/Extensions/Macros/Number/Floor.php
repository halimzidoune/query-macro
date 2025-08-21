<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Number;

use Hz\QueryMacroHelper\Extensions\BaseMacro;
/**
 * FLOOR Macro - Rounds numbers down to the nearest integer
 *
 * Provides cross-database floor functionality:
 * - MySQL/PostgreSQL/Oracle/SQL Server: FLOOR()
 * - SQLite: Special implementation using CAST and CASE
 */
class Floor extends BaseMacro
{
    public static function name(): string
    {
        return 'selectFloor';
    }

    public function mysql($column): string
    {
        return $this->defaultExpression($column);
    }

    public function defaultExpression($column): string
    {
        return "FLOOR($column)";
    }

    public function pgsql($column): string
    {
        return $this->defaultExpression($column);
    }

    public function sqlsrv($column): string
    {
        return $this->defaultExpression($column);
    }

    public function oracle($column): string
    {
        return $this->defaultExpression($column);
    }

    public function sqlite($column): string
    {
        // SQLite implementation using CAST with CASE for proper flooring
        return "CASE WHEN $column = CAST($column AS INTEGER) THEN $column " .
            "WHEN $column > 0 THEN CAST($column AS INTEGER) " .
            "ELSE CAST($column AS INTEGER) - 1 END";
    }
}
