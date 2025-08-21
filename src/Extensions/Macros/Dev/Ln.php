<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Dev;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * SelectLn - Calculates natural logarithm (base e)
 * Example: LN(7.389) ≈ 2
 */
class Ln extends BaseMacro
{
    public static function name(): string
    {
        return 'selectLn';
    }

    public function defaultExpression($column): string
    {
        return "LN($column)";
    }
    public function sqlite($column): string {
        return <<<SQL
    CASE 
        WHEN $column IS NULL THEN NULL
        WHEN $column < 0 THEN NULL
        WHEN $column = 0 THEN 0
        WHEN $column = 1 THEN 1
        ELSE (
            -- Babylonian method (Heron's method) implementation
            WITH RECURSIVE sqrt_approx(iter, guess) AS (
                SELECT 1, 
                    CASE 
                        WHEN $column > 1 THEN $column/2.0
                        ELSE $column * 0.5 + 0.5  -- Better starting guess for numbers < 1
                    END
                UNION ALL
                SELECT iter+1, (guess + $column/guess)/2.0
                FROM sqrt_approx
                WHERE iter < 10  -- Maximum iterations
                AND ABS(guess * guess - $column) > 1e-8  -- Stop when sufficiently accurate
            )
            SELECT guess FROM sqrt_approx
            ORDER BY ABS(guess * guess - $column)
            LIMIT 1
        )
    END
    SQL;
    }
}