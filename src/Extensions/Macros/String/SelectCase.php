<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\String;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

class SelectCase extends BaseMacro
{
    public static function name(): string
    {
        return 'selectCase';
    }

    public function defaultExpression(string $column, array $cases, ?string $else = null): string
    {
        $sql = "CASE $column";

        foreach ($cases as $when => $then) {
            $sql .= " WHEN " . $this->quoteValue($when) . " THEN " . $this->quoteValue($then);
        }

        if ($else !== null) {
            $sql .= " ELSE " . $this->quoteValue($else);
        }
        return $sql . " END";
    }

    public function mysql(string $column, array $cases, ?string $else = null): string
    {
        return $this->defaultExpression($column, $cases, $else);
    }

    public function pgsql(string $column, array $cases, ?string $else = null): string
    {
        return $this->defaultExpression($column, $cases, $else);
    }

    public function sqlsrv(string $column, array $cases, ?string $else = null): string
    {
        return $this->defaultExpression($column, $cases, $else);
    }

    protected function quoteValue($value): string
    {
        if(is_bool($value)){
            return (int)$value;
        }
        if (is_numeric($value) && !is_string($value)) {
            return (string)$value;
        }

        return "'" . addslashes((string)$value) . "'";
    }
}