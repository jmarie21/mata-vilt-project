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
        Schema::table('timesheets', function (Blueprint $table) {
            // Drop the existing `date` column
            $table->dropColumn('date');

            // Add the `start_time` column
            $table->dateTime('start_time');  // This can store both the date and time
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timesheets', function (Blueprint $table) {
            // Revert the changes in case of rollback
            $table->dropColumn('start_time');
            $table->date('date');  // Recreate the original `date` column
        });
    }
};
