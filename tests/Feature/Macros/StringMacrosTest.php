<?php

namespace Hz\QueryMacroHelper\Tests\Feature\Macros;

use Hz\QueryMacroHelper\Tests\TestCase;
use Illuminate\Support\Facades\DB;

class StringMacrosTest extends TestCase
{
    public function test_select_concat_concatenates_values(): void
    {
        DB::table('samples')->insert([
            ['name' => 'Alice', 'designation' => 'Manager'],
        ]);

        $row = DB::table('samples')
            ->selectConcat('full_label', 'name', str(' - '), 'designation')
            ->where('name', 'Alice')
            ->first();

        $this->assertSame('Alice - Manager', $row->full_label);
    }

    public function test_select_upper_and_lower_and_length(): void
    {
        DB::table('samples')->insert([
            ['name' => 'Alice'],
        ]);
        $rowU = DB::table('samples')->selectUpper('name as up')->first();
        $rowL = DB::table('samples')->selectLower('name as low')->first();
        $rowLen = DB::table('samples')->selectLength('name as len')->first();

        $this->assertSame('ALICE', $rowU->up);
        $this->assertSame('alice', $rowL->low);
        $this->assertSame(5, (int) $rowLen->len);
    }

    public function test_select_substring_zero_based(): void
    {
        DB::table('samples')->insert([
            ['name' => 'X', 'designation' => 'Manager'],
        ]);
        $row = DB::table('samples')->selectSubstring('designation as firstTwo', 0, 2)->first();
        $this->assertSame('Ma', $row->firstTwo);
    }

    public function test_select_replace_and_trim_and_pad(): void
    {
        DB::table('samples')->insert([
            ['name' => 'Bob', 'designation' => '  Chief  '],
        ]);
        $rowTrim = DB::table('samples')->selectTrim('designation as t')->first();
        $rowPad = DB::table('samples')->selectPad('name as padded', 7, '0', 'left')->first();

        $this->assertSame('Chief', $rowTrim->t);
        $this->assertSame('0000Bob', $rowPad->padded);

        DB::table('samples')->where('name', 'Bob')->update(['designation' => 'Manager']);
        $rowRep = DB::table('samples')->selectReplace('designation as rep', 'Man', 'Sup')->first();
        $this->assertSame('Supager', $rowRep->rep);
    }

    public function test_starts_ends_contains_regexp_and_slug_and_case(): void
    {
        DB::table('samples')->insert([
            ['name' => 'Alice', 'designation' => 'Dev', 'email' => 'alice@example.com'],
        ]);

        $rowSw = DB::table('samples')->selectStartsWith('name as sw', 'Al')->first();
        $rowEw = DB::table('samples')->selectEndsWith('designation as ew', 'ev')->first();
        $rowC = DB::table('samples')->selectContains('designation as c', 'e')->first();
        $rowR = DB::table('samples')->selectRegexp('email as r', '^[a-z]+@')->first();

        $this->assertSame(1, (int) $rowSw->sw);
        $this->assertSame(1, (int) $rowEw->ew);
        $this->assertSame(1, (int) $rowC->c);
        $this->assertSame(1, (int) $rowR->r);

        DB::table('samples')->update(['designation' => 'Head of Ops']);
        $rowSlug = DB::table('samples')->selectSlug('designation as slug')->first();
        $this->assertSame('head-of-ops', $rowSlug->slug);

        DB::table('samples')->update(['designation' => 'Manager']);
        $rowCase = DB::table('samples')->selectCase('designation as label', [
            'Manager' => 'MGR',
            'Dev' => 'DEV',
        ], 'OTHER')->first();
        $this->assertSame('MGR', $rowCase->label);
    }
} 