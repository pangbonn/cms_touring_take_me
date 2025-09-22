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
        Schema::table('web_configs', function (Blueprint $table) {
            $table->string('license_number')->nullable()->comment('หมายเลขใบอนุญาตประกอบธุรกิจท่องเที่ยว');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('web_configs', function (Blueprint $table) {
            $table->dropColumn('license_number');
        });
    }
};
