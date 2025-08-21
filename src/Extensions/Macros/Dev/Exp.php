<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Number;


namespace Hz\QueryMacroHelper\Extensions\Macros\Number;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * SelectExp - Calculates exponential value (e^x)
 * Example: e^2 ≈ 7.389
 */
class Exp extends BaseMacro
{
    public static function name(): string
    {
        return 'selectExp';
    }

    public function defaultExpression($column): string
    {
        return "EXP($column)";
    }

    public function sqlite($column): string {
        return <<<SQL
    CASE 
        WHEN $column IS NULL THEN NULL
        WHEN $column < 0 THEN NULL
        WHEN $column = 0 THEN 0
        WHEN $column = 1 THEN 1
        ELSE (
            WITH RECURSIVE sqrt_approx(iter, guess) AS (
                SELECT 1, $column/2.0
                UNION ALL
                SELECT iter+1, (guess + $column/guess)/2.0
                FROM sqrt_approx
                WHERE iter < 6  -- Reduced to 6 iterations for better performance
                AND ABS(guess * guess - $column) > 0.0001  -- Stop when close enough
            )
            SELECT guess FROM sqrt_approx 
            ORDER BY ABS(guess * guess - $column)
            LIMIT 1
        )
    END
    SQL;
    }
}