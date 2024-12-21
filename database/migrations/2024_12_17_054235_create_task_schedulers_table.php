<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_schedulers', function (Blueprint $table) {
            $table->id();
            $table->string('interval')->default('everyMinute'); // Store the interval (everyMinute, everyFiveMinutes, hourly, daily)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_schedulers');
    }
};
