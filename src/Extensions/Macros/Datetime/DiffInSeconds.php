<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Datetime;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

class DiffInSeconds extends BaseMacro
{
    public static function name(): string {
        return 'selectDiffInSeconds';
    }

    public function defaultExpression($column1, $column2): string {
        return "EXTRACT(EPOCH FROM ($column2 - $column1))";
    }

    public function mysql($column1, $column2): string {
        return "TIMESTAMPDIFF(SECOND, $column1, $column2)";
    }

    public function pgsql($column1, $column2): string {
        return "EXTRACT(EPOCH FROM ($column2 - $column1))";
    }

    public function sqlsrv($column1, $column2): string {
        return "DATEDIFF(SECOND, $column1, $column2)";
    }

    public function oracle($column1, $column2): string {
        return "($column2 - $column1) * 24 * 60 * 60";
    }

    public function sqlite($column1, $column2): string {
        return "strftime('%s', $column2) - strftime('%s', $column1)";
    }
}