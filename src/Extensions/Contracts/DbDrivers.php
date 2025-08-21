<?php

namespace Hz\QueryMacroHelper\Extensions\Contracts;

interface DbDrivers
{
    public function mysql(array $arguments): string;

    public function pgsql(array $arguments): string;

    public function sqlite(array $arguments): string;

    public function oracle(array $arguments): string;

    public function sqlsrv(array $arguments): string;
}