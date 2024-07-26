<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->double('amount');
            $table->date('effective_date')->nullable()->defaultValue;
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
