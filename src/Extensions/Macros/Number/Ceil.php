<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Number;

use Hz\QueryMacroHelper\Extensions\BaseMacro;
/**
 * CEIL Macro - Rounds numbers up to the nearest integer
 *
 * Provides database-agnostic ceiling functionality with these behaviors:
 * - For positive numbers: 3.2 → 4
 * - For negative numbers: -2.7 → -2
 * - For integers: returns the same value
 * - NULL handling: returns NULL if input is NULL
 */
class Ceil extends BaseMacro
{
    public static function name(): string
    {
        return 'selectCeil';
    }

    public function mysql($column): string
    {
        return $this->defaultExpression($column);
    }

    public function defaultExpression($column): string
    {
        return "CEIL($column)";
    }

    public function pgsql($column): string
    {
        return "CEIL($column)";
    }

    public function sqlsrv($column): string
    {
        return "CEILING($column)";
    }

    public function oracle($column): string
    {
        return "CEIL($column)";
    }

    public function sqlite($column): string
    {
        // Alternative using ROUND() with offset
        return "CASE WHEN $column > 0 THEN ROUND($column + 0.5) ELSE ROUND($column - 0.5) END";
    }

}