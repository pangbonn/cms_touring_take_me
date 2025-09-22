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
        Schema::table('trip_schedules', function (Blueprint $table) {
            // Make max_participants nullable and set default to 0
            $table->integer('max_participants')->nullable()->default(0)->change();
            
            // Make price nullable and set default to 0
            $table->decimal('price', 10, 2)->nullable()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trip_schedules', function (Blueprint $table) {
            // Revert back to original structure
            $table->integer('max_participants')->default(0)->change();
            $table->decimal('price', 10, 2)->nullable()->change();
        });
    }
};
