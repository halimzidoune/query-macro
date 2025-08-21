<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Number;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

class Random extends BaseMacro
{
    public function hasColumn(): bool
    {
        return false;
    }

    public static function name(): string
    {
        return 'selectRandom';
    }

    public function defaultExpression($alias): string
    {
        return "RAND() AS ".$alias;
    }

    public function mysql($alias): string
    {
        return "RAND() AS ".$alias;
    }

    public function pgsql($alias): string
    {
        return "RANDOM() AS ".$alias;
    }

    public function sqlsrv($alias): string
    {
        return "RAND() AS ".$alias;
    }

    public function oracle($alias): string
    {
        return "DBMS_RANDOM.VALUE AS ".$alias;
    }

    public function sqlite($alias): string
    {
        return "RANDOM() AS ".$alias;
    }
}