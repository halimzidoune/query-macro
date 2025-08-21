<?php
namespace Hz\QueryMacroHelper\Extensions\Macros\Number;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * SelectRandomBetween - Generates random number within range
 * Example: Random between 1-100
 */
class RandomBetween extends BaseMacro
{
    public function hasColumn(): bool
    {
        return false;
    }

    public static function name(): string
    {
        return 'selectRandomBetween';
    }

    public function defaultExpression($alias, $min, $max): string
    {
        return "FLOOR(RAND() * ($max - $min + 1) + $min) AS ".$alias;
    }

    public function mysql($alias, $min, $max): string
    {
        return $this->defaultExpression($alias, $min, $max);
    }

    public function pgsql($alias, $min, $max): string
    {
        return "FLOOR(RANDOM() * ($max - $min + 1) + $min) AS ".$alias;
    }

    public function sqlsrv($alias, $min, $max): string
    {
        return "FLOOR(RAND() * ($max - $min + 1) + $min) AS ".$alias;
    }

    public function oracle($alias, $min, $max): string
    {
        return "FLOOR(DBMS_RANDOM.VALUE($min, $max + 1)) AS ".$alias;
    }

    public function sqlite($alias, $min, $max): string
    {
        return "ABS(RANDOM()) % ($max - $min + 1) + $min AS ".$alias;
    }
}