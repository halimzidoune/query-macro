<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\String;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

class Substring extends BaseMacro
{
    public static function name(): string {
        return 'selectSubstring';
    }

    public function defaultExpression($column, $start, $length = null): string {
        $length = $length ? ", $length" : '';
        return "SUBSTRING($column, $start$length)";
    }

    public function mysql($column, $start, $length = null): string {
        return $this->defaultExpression($column, $start, $length);
    }

    public function pgsql($column, $start, $length = null): string {
        return $length ? "SUBSTR($column, $start, $length)" : "SUBSTR($column, $start)";
    }

    public function sqlsrv($column, $start, $length = null): string {
        return $length ? "SUBSTRING($column, $start, $length)" : "SUBSTRING($column, $start, LEN($column))";
    }

    public function oracle($column, $start, $length = null): string {
        return $length ? "SUBSTR($column, $start, $length)" : "SUBSTR($column, $start)";
    }
}