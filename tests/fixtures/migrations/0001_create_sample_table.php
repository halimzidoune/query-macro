<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('samples', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('designation')->nullable();
            $table->integer('count')->nullable();
            $table->float('price')->nullable();
            $table->date('birth_date')->nullable();
            $table->dateTime('created_at_col')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samples');
    }
}; 