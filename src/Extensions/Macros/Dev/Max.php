<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Dev;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * SelectMax - Finds the largest value
 * Example: MAX(score)
 */
class Max extends BaseMacro
{
    public static function name(): string
    {
        return 'selectMax';
    }

    public function defaultExpression(...$columns): string
    {
        return "GREATEST(" . implode(', ', $columns) . ")";
    }

    public function sqlite(...$columns): string {
        // SQLite implementation using CASE expressions
        return $this->buildCaseExpression($columns, '>');
    }

    protected function buildCaseExpression(array $columns, string $operator): string
    {
        if (count($columns) === 0) {
            return 'NULL';
        }

        if (count($columns) === 1) {
            return $columns[0];
        }

        $current = array_shift($columns);
        $next = array_shift($columns);

        $expression = "CASE WHEN $current $operator $next THEN $current ELSE $next END";

        foreach ($columns as $column) {
            $expression = "CASE WHEN $expression $operator $column THEN $expression ELSE $column END";
        }

        return "($expression)";
    }
}