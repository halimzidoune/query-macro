<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Casts;


use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * Macro for casting values to literal boolean true/false
 */
class SelectBoolean extends BaseMacro
{
    public static function name(): string
    {
        return 'selectBoolean';
    }

    public function defaultExpression($column): string
    {
        return "CASE WHEN $column THEN true ELSE false END";
    }

    public function mysql($column): string
    {
        return "IF($column, true, false)";
    }

    public function pgsql($column): string
    {
        return "($column)::boolean";
    }

    public function sqlsrv($column): string
    {
        return "CAST(CASE WHEN $column THEN 1 ELSE 0 END AS BIT)";
    }

    public function oracle($column): string
    {
        return "CASE WHEN $column THEN 1 ELSE 0 END";
    }

    public function sqlite($column, ?string $alias = null): string
    {
        $expr = "CASE 
            WHEN $column IN (1, '1', 'true', 't', 'yes', 'y', 'on') THEN 1
            WHEN $column IN (0, '0', 'false', 'f', 'no', 'n', 'off') THEN 0
            WHEN $column IS NULL THEN 0
            ELSE CAST($column AS INTEGER)
        END";
        return $alias ? "$expr AS $alias" : $expr;
    }

}