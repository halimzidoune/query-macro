<?php

namespace Hz\QueryMacroHelper\Tests\Feature\Macros;

use Hz\QueryMacroHelper\Tests\TestCase;
use Illuminate\Support\Facades\DB;

class DatetimeMacrosTest extends TestCase
{
    protected function seedRows(): void
    {
        DB::table('samples')->insert([
            ['name' => 'Alice', 'birth_date' => '2000-02-29', 'created_at_col' => '2024-12-31 23:15:00'],
            ['name' => 'Bob', 'birth_date' => '1990-01-15', 'created_at_col' => '2024-01-01 00:45:00'],
        ]);
    }

    public function test_format_year(): void
    {
        $this->seedRows();
        $row = DB::table('samples')->selectFormatDate('birth_date as y', 'Y')->where('name','Alice')->first();
        $this->assertSame('2000', $row->y);
    }

    public function test_age_is_numeric(): void
    {
        $this->seedRows();
        $row = DB::table('samples')->selectAge('birth_date as age')->where('name','Bob')->first();
        $this->assertGreaterThanOrEqual(30, (int) $row->age);
    }

    public function test_start_and_end_of_year(): void
    {
        $this->seedRows();
        $row = DB::table('samples')
            ->selectStartOfYear('created_at_col as soy')
            ->selectEndOfYear('created_at_col as eoy')
            ->where('name','Alice')
            ->first();
        $this->assertSame('2024-01-01 00:00:00', $row->soy);
        $this->assertSame('2024-12-31 23:59:59', $row->eoy);
    }

    public function test_week_of_year_and_diff_in_minutes(): void
    {
        $this->seedRows();
        $rowW = DB::table('samples')->selectWeekOfYear('created_at_col as woy')->where('name','Bob')->first();
        $this->assertGreaterThan(0, (int) $rowW->woy);

        $rowD = DB::table('samples')->selectDiffInMinutes('created_at_col as d', '2024-01-01 01:45:00')->where('name','Bob')->first();
        $this->assertSame(60, (int) $rowD->d);
    }
} 