<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Number;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

class Modulo extends BaseMacro
{
    public static function name(): string
    {
        return 'selectMod';
    }

    public function mysql($dividend, $divisor): string
    {
        return $this->defaultExpression($dividend, $divisor);
    }

    public function defaultExpression($dividend, $divisor): string
    {
        return "MOD($dividend, $divisor)";
    }

    public function pgsql($dividend, $divisor): string
    {
        return "$dividend % $divisor";
    }

    public function sqlsrv($dividend, $divisor): string
    {
        return "$dividend % $divisor";
    }

    public function oracle($dividend, $divisor): string
    {
        return "MOD($dividend, $divisor)";
    }

    public function sqlite($dividend, $divisor): string
    {
        return "$dividend % $divisor";
    }
}