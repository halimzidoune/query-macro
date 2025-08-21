<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Number;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * POWER Macro - Raises a number to a specified power
 *
 * Provides cross-database power functionality:
 * - MySQL/SQL Server/Oracle: POWER()
 * - PostgreSQL: POW() or ^ operator
 * - SQLite: Special implementation using multiplication
 */
class Power extends BaseMacro
{
    public static function name(): string
    {
        return 'selectPower';
    }

    public function defaultExpression($base, $exponent): string
    {
        return "POWER($base, $exponent)";
    }

    public function mysql($base, $exponent): string
    {
        return $this->defaultExpression($base, $exponent);
    }

    public function pgsql($base, $exponent): string
    {
        // PostgreSQL supports both POW() and ^ operator
        return "POW($base, $exponent)";
    }

    public function sqlsrv($base, $exponent): string
    {
        return $this->defaultExpression($base, $exponent);
    }

    public function oracle($base, $exponent): string
    {
        return $this->defaultExpression($base, $exponent);
    }

    public function sqlite($base, $exponent): string
    {
        // SQLite doesn't have POWER() function, so we use this workaround:
        if (is_numeric($exponent)) {
        $exp = (int)$exponent;
        if ($exp === 0) return '1';
        if ($exp === 1) return $base;
        if ($exp === 2) return "($base * $base)";
        if ($exp === 3) return "($base * $base * $base)";
    }

        // Fallback for non-integer exponents or exponents > 3
        return "EXP($exponent * LN($base))";
    }
}