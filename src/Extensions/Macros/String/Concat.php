<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\String;

use Hz\QueryMacroHelper\Extensions\BaseMacro;
use Illuminate\Support\Stringable;

/**
 * Macro: selectConcat
 * Purpose: Concatenate columns and literals into a single string across drivers.
 * Example:
 *   - Given: name = "Alice", designation = "Manager"
 *   - Usage: ->selectConcat('full_label', 'name', str(' - '), 'designation')
 *   - Result: full_label = "Alice - Manager"
 */
class Concat extends BaseMacro
{
    public function hasColumn(): bool
    {
        return false;
    }

    public static function name(): string
    {
        return 'selectConcat';
    }

    protected function getPiecesFromArgs($args): array
    {
        $pieces = [];
        for ($i = 0; $i < count($args); $i++) {
            if(is_string($args[$i])){
                $pieces[] = $args[$i];
            }elseif($args[$i] instanceof  Stringable){
                $pieces[] = "'".$args[$i]."'";
            }
        }
        return $pieces;
    }

    /**
     * Default expression - assumes MySQL style CONCAT syntax.
     *
     * @param string $alias
     * @param mixed ...$args
     * @return string
     */
    public function defaultExpression($alias, ...$args): string
    {
        return 'CONCAT(' . implode(', ', $this->getPiecesFromArgs($args)) . ') AS ' . $alias;
    }

    /**
     * SQLite concatenation using || operator.
     *
     * @param string $alias
     * @param mixed ...$args
     * @return string
     */
    public function sqlite($alias, ...$args): string
    {
        return implode(' || ', $this->getPiecesFromArgs($args)) . ' AS ' . $alias;
    }

    /**
     * MySQL concatenation using CONCAT.
     *
     * @param string $alias
     * @param mixed ...$args
     * @return string
     */
    public function mysql($alias, ...$args): string
    {
        return $this->defaultExpression($alias, ...$args);
    }

    /**
     * PostgreSQL concatenation using || operator.
     *
     * @param string $alias
     * @param mixed ...$args
     * @return string
     */
    public function pgsql($alias, ...$args): string
    {
        return implode(' || ', $this->getPiecesFromArgs($args)) . ' AS ' . $alias;
    }

    /**
     * SQL Server concatenation using + and ISNULL to handle NULLs.
     *
     * @param string $alias
     * @param mixed ...$args
     * @return string
     */
    public function sqlsrv($alias, ...$args): string
    {
        $pieces = $this->getPiecesFromArgs($args);
        $expr = '';
        $count = count($pieces);
        foreach ($pieces as $i => $piece) {
            // Wrap each piece in ISNULL to avoid NULL breaking concat
            $expr .= 'ISNULL(' . $piece . ", '')";
            if ($i < $count - 1) {
                $expr .= " + ' ' + ";
            }
        }
        return $expr . ' AS ' . $alias;
    }


}