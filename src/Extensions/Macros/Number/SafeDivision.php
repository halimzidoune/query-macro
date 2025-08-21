<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Number;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

class SafeDivision extends BaseMacro
{
    public static function name(): string
    {
        return 'selectSafeDivision';
    }

    public function mysql($numerator, $denominator): string
    {
        return $this->defaultExpression($numerator, $denominator);
    }

    public function defaultExpression($numerator, $denominator): string
    {
        return "($numerator / NULLIF($denominator, 0))";
    }

    public function pgsql($numerator, $denominator): string
    {
        return $this->defaultExpression($numerator, $denominator);
    }

    public function sqlsrv($numerator, $denominator): string
    {
        return "CASE WHEN $denominator = 0 THEN NULL ELSE $numerator / $denominator END";
    }

    public function oracle($numerator, $denominator): string
    {
        return "CASE WHEN $denominator = 0 THEN NULL ELSE $numerator / $denominator END";
    }

    public function sqlite($numerator, $denominator): string
    {
        return $this->defaultExpression($numerator, $denominator);
    }
}