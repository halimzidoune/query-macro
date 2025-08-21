<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Datetime;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

class DiffInMinutes extends BaseMacro
{
    public static function name(): string
    {
        return 'selectDiffInMinutes';
    }

    public function defaultExpression($column1, $column2): string
    {
        return "EXTRACT(EPOCH FROM ($column2 - $column1)) / 60";
    }

    public function mysql($column1, $column2): string
    {
        return "TIMESTAMPDIFF(MINUTE, $column1, $column2)";
    }

    public function pgsql($column1, $column2): string
    {
        return "EXTRACT(EPOCH FROM ($column2 - $column1)) / 60";
    }

    public function sqlsrv($column1, $column2): string
    {
        return "DATEDIFF(MINUTE, $column1, $column2)";
    }

    public function oracle($column1, $column2): string
    {
        return "($column2 - $column1) * 24 * 60";
    }

    public function sqlite($column1, $column2): string
    {
        return "(strftime('%s', $column2) - strftime('%s', $column1)) / 60";
    }

    // Optional: Add precision parameter
    public function withPrecision($column1, $column2, int $decimals = 0): string
    {
        $divisor = $decimals > 0 ? "::numeric(20,$decimals)" : "";
        return "ROUND((" . $this->defaultExpression($column1, $column2) . ")$divisor)";
    }
}