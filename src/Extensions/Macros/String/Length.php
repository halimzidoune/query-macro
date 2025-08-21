<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\String;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

class Length extends BaseMacro
{

    public static function name(): string
    {
        return 'selectLength';
    }

    public function defaultExpression($column): string
    {
        return "LENGTH($column)";
    }

    public function mysql($column): string
    {
        return "CHAR_LENGTH($column)";
    }

    public function pgsql($column): string
    {
        return "CHAR_LENGTH($column)";
    }

    public function sqlsrv($column): string
    {
        return "LEN($column)";
    }
}