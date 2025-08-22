<?php

namespace Hz\QueryMacroHelper\Tests\Feature\Macros;

use Hz\QueryMacroHelper\Tests\TestCase;
use Illuminate\Support\Facades\DB;

class CastMacrosTest extends TestCase
{
    protected function seedRow(): void
    {
        DB::table('samples')->insert([
            ['name' => 'Alice', 'email' => 'alice@example.com', 'designation' => '1', 'count' => 5, 'price' => 10.75, 'birth_date' => '2024-01-02', 'created_at_col' => '2024-01-02 03:04:05'],
        ]);
    }

    public function test_string_and_integer_and_float_and_boolean(): void
    {
        $this->seedRow();
        $rowS = DB::table('samples')->selectString('count as s')->first();
        $rowI = DB::table('samples')->selectInteger('designation as i')->first();
        $rowF = DB::table('samples')->selectFloat('price as f', 2)->first();
        $rowB = DB::table('samples')->selectBoolean('designation as b')->first();
        $this->assertSame('5', $rowS->s);
        $this->assertSame(1, (int) $rowI->i);
        $this->assertSame(10.75, (float) $rowF->f);
        $this->assertSame(1, (int) $rowB->b);
    }

    public function test_date_and_datetime(): void
    {
        $this->seedRow();
        $rowD = DB::table('samples')->selectDate('created_at_col as d')->first();
        $rowDT = DB::table('samples')->selectDateTime('birth_date as dt')->first();
        $this->assertSame('2024-01-02', $rowD->d);
        $this->assertSame('2024-01-02 00:00:00', $rowDT->dt);
    }
} 