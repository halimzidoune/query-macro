<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Number;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

class Round extends BaseMacro
{
    public static function name(): string
    {
        return 'selectRound';
    }

    public function mysql($column, $precision = 0): string
    {
        return $this->defaultExpression($column, $precision);
    }

    public function defaultExpression($column, $precision = 0): string
    {
        return "ROUND($column, $precision)";
    }

    public function pgsql($column, $precision = 0): string
    {
        return "ROUND($column::numeric, $precision)";
    }

    public function sqlsrv($column, $precision = 0): string
    {
        return $this->defaultExpression($column, $precision);
    }

    public function oracle($column, $precision = 0): string
    {
        return "ROUND($column, $precision)";
    }

    public function sqlite($column, $precision = 0): string
    {
        return "ROUND($column, $precision)";
    }
}