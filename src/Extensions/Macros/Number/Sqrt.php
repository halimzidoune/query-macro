<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Number;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * SelectSqrt - Calculates square root of a number
 * Example: √25 → 5
 */
class Sqrt extends BaseMacro
{
    public static function name(): string
    {
        return 'selectSqrt';
    }

    public function defaultExpression($column): string
    {
        return "SQRT($column)";
    }

    public function sqlite($column): string {
        // SQLite implementation using Newton-Raphson approximation
        return <<<SQL
            CASE WHEN $column IS NULL THEN NULL
            WHEN $column < 0 THEN NULL
            WHEN $column = 0 THEN 0
            ELSE (
                WITH RECURSIVE sqrt_iteration(n, guess) AS (
                    SELECT 1, $column/2.0
                    UNION ALL
                    SELECT n+1, (guess + $column/guess)/2.0
                    FROM sqrt_iteration
                    WHERE n < 20  -- 20 iterations for reasonable accuracy
                )
                SELECT guess FROM sqrt_iteration ORDER BY n DESC LIMIT 1
            )
            END
        SQL;
    }
}