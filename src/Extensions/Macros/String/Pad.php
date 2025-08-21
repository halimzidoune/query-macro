<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\String;


use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * SelectPad - Pads a string to a certain length
 * Example: LPAD('5', 3, '0', 'left') → "005"
 */
class Pad extends BaseMacro
{
    public static function name(): string
    {
        return 'selectPad';
    }

    public function pgsql($column, $length, $padString = ' ', $type = 'left'): string
    {
        return $this->mysql($column, $length, $padString, $type);
    }

    public function mysql($column, $length, $padString = ' ', $type = 'left'): string
    {
        return $type === 'left'
            ? $this->left($column, $length, $padString)
            : $this->right($column, $length, $padString);
    }

    public function left($column, $length, $padString = ' '): string
    {
        return "LPAD($column, $length, '$padString')";
    }

    public function right($column, $length, $padString = ' '): string
    {
        return "RPAD($column, $length, '$padString')";
    }

    public function sqlsrv($column, $length, $padString = ' ', $type = 'left'): string
    {
        $padLen = "$length - LEN($column)";
        $pad = "REPLICATE('$padString', $padLen)";
        return $type === 'left'
            ? "RIGHT($pad + $column, $length)"
            : "LEFT($column + $pad, $length)";
    }

    public function oracle($column, $length, $padString = ' ', $type = 'left'): string
    {
        return $this->mysql($column, $length, $padString, $type);
    }

    public function sqlite($column, $length, $padString = ' ', $type = 'left'): string
    {
        $padLen = "$length - LENGTH($column)";
        $pad = "SUBSTR('" . str_repeat($padString, 100) . "', 1, $padLen)";
        return $type === 'left'
            ? "SUBSTR($pad || $column, -$length)"
            : "SUBSTR($column || $pad, 1, $length)";
    }
}