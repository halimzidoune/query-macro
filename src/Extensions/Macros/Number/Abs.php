<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Number;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

class Abs extends BaseMacro
{
    public static function name(): string
    {
        return 'selectAbs';
    }

    public function mysql($column): string
    {
        return $this->defaultExpression($column);
    }

    public function defaultExpression($column): string
    {
        return "ABS($column)";
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
        return $this->defaultExpression($column);
    }
}