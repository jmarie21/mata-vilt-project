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
        Schema::create('click_up_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_id')->unique(); // Add this column to store the unique task ID
            $table->string('name');
            $table->text('description')->nullable(); // Make description nullable
            $table->string('status');
            $table->string('creator')->nullable(); // Make creator nullable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('click_up_tasks');
    }
};
