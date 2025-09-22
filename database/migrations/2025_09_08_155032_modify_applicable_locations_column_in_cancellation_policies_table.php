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
        Schema::table('cancellation_policies', function (Blueprint $table) {
            // Change applicable_locations from string to json
            $table->json('applicable_locations')->nullable()->change()->comment('สถานที่ที่ใช้บังคับ (JSON)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cancellation_policies', function (Blueprint $table) {
            // Revert back to string
            $table->string('applicable_locations')->nullable()->change()->comment('สถานที่ที่ใช้บังคับ (JSON)');
        });
    }
};