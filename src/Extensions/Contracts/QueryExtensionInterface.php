<?php

namespace Hz\QueryMacroHelper\Extensions\Contracts;

interface QueryExtensionInterface
{

    public static function name(): string;

    public function getExpression(string $driver, ...$args): string;
}