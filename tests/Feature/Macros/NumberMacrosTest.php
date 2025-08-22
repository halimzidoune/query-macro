<?php

namespace Hz\QueryMacroHelper\Tests\Feature\Macros;

use Hz\QueryMacroHelper\Tests\TestCase;
use Illuminate\Support\Facades\DB;

class NumberMacrosTest extends TestCase
{
    protected function seedRows(): void
    {
        DB::table('samples')->insert([
            ['name' => 'Alice', 'count' => 5, 'price' => 10.5],
            ['name' => 'Bob', 'count' => 12, 'price' => 20.25],
        ]);
    }

    public function test_basic_arithmetic(): void
    {
        $this->seedRows();
        $rowAdd = DB::table('samples')->selectAdd('count as v', 'count', 3)->where('name','Alice')->first();
        $rowSub = DB::table('samples')->selectSubtract('count as v', 'count', 2)->where('name','Bob')->first();
        $rowMul = DB::table('samples')->selectMultiply('count as v', 'count', 2)->where('name','Alice')->first();
        $this->assertSame(8, (int) $rowAdd->v);
        $this->assertSame(10, (int) $rowSub->v);
        $this->assertSame(10, (int) $rowMul->v);
    }

    public function test_abs_round_floor_ceil(): void
    {
        $this->seedRows();
        DB::table('samples')->where('name','Alice')->update(['count' => -7]);
        $rowAbs = DB::table('samples')->selectAbs('count as v')->where('name','Alice')->first();
        $rowRound = DB::table('samples')->selectRound('price as v', 1)->where('name','Bob')->first();
        $rowFloor = DB::table('samples')->selectFloor('price as v')->where('name','Alice')->first();
        $rowCeil = DB::table('samples')->selectCeil('price as v')->where('name','Alice')->first();
        $this->assertSame(7, (int) $rowAbs->v);
        $this->assertSame(20.3, (float) $rowRound->v);
        $this->assertSame(10, (int) $rowFloor->v);
        $this->assertSame(11, (int) $rowCeil->v);
    }

    public function test_power_sqrt_modulo_percent_truncate(): void
    {
        $this->seedRows();
        DB::table('samples')->where('name','Alice')->update(['count' => 9]);
        $rowPow = DB::table('samples')->selectPower('count as v', 2)->where('name','Bob')->first();
        $rowSqrt = DB::table('samples')->selectSqrt('count as v')->where('name','Alice')->first();
        $rowMod = DB::table('samples')->selectModulo('count as v', 5)->where('name','Bob')->first();
        $rowPct = DB::table('samples')->selectPercent('count as v', 50)->where('name','Bob')->first();
        $rowTrunc = DB::table('samples')->selectTruncate('price as v', 1)->where('name','Bob')->first();
        $this->assertSame(144, (int) $rowPow->v);
        $this->assertSame(3, (int) round($rowSqrt->v));
        $this->assertSame(2, (int) $rowMod->v);
        $this->assertSame(6, (int) $rowPct->v);
        $this->assertSame(20.2, (float) $rowTrunc->v);
    }

    public function test_random_and_safe_division(): void
    {
        $this->seedRows();
        $rowRand = DB::table('samples')->selectRandomBetween('randv', 1, 3)->first();
        $this->assertGreaterThanOrEqual(1, $rowRand->randv);
        $this->assertLessThanOrEqual(3, $rowRand->randv);

        DB::table('samples')->where('name','Alice')->update(['count' => 0]);
        $rowDiv = DB::table('samples')->selectSafeDivision('div as v', 'price', 'count', 0)->where('name','Alice')->first();
        $this->assertSame(0, (int) $rowDiv->v);
    }
} 