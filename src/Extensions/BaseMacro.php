<?php

namespace Hz\QueryMacroHelper\Extensions;

use Hz\QueryMacroHelper\Extensions\Contracts\DbDrivers;
use Hz\QueryMacroHelper\Extensions\Contracts\QueryExtensionInterface;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\select;

abstract class BaseMacro implements QueryExtensionInterface
{
    protected string $driver;


    public function hasColumn(): bool
    {
        return true;
    }


    public function __construct()
    {
        $this->driver = DB::getDriverName();
    }

    public static function make(): static
    {
        return new static();
    }

    public function getExpression(string $driver, ...$args): string
    {
        $default= "defaultExpression";
        if (!method_exists($this, $driver) && !method_exists($this, "defaultExpression")) {
            throw new \RuntimeException("The methode $default, and method [$driver] not written in " . static::class);
        }

        if(method_exists($this, $driver)){
            return $this->{$driver}(...$args);
        }else{
            return $this->{$default}(...$args);
        }
    }

}
