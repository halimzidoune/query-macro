<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\String;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

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
        return "$column LIKE '$value%'";
    }

    public function oracle($column, $value): string
    {
        return "REGEXP_LIKE($column, '^$value')";
    }
}