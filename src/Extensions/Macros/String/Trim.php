<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\String;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

class Trim extends BaseMacro
{
    public static function name(): string
    {
        return 'selectTrim';
    }

    public function mysql($column): string
    {
        return $this->defaultExpression($column);
    }

    public function defaultExpression($column): string
    {
        return "TRIM($column)";
    }

    public function pgsql($column): string
    {
        return $this->defaultExpression($column);
    }

    public function sqlsrv($column): string
    {
        return "LTRIM(RTRIM($column))";
    }

    public function oracle($column): string
    {
        return $this->defaultExpression($column);
    }
}