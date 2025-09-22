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
        Schema::create('trip_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->onDelete('cascade'); // รหัสทริป
            $table->date('departure_date'); // วันที่ออกเดินทาง
            $table->date('return_date')->nullable(); // วันที่กลับ (ถ้ามี)
            $table->integer('max_participants')->default(0); // จำนวนผู้เข้าร่วมสูงสุด
            $table->decimal('price', 10, 2)->nullable(); // ราคา
            $table->boolean('is_active')->default(true); // สถานะการใช้งาน
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_schedules');
    }
};
