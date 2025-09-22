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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ชื่อทริป
            $table->string('image')->nullable(); // รูปภาพ
            $table->text('itinerary')->nullable(); // แผนการเดินทาง
            $table->text('total_cost')->nullable(); // ค่าใช้จ่ายรวม
            $table->text('personal_items')->nullable(); // ของส่วนตัวที่ต้องเตรียม
            $table->text('area_info')->nullable(); // คำแนะนำและข้อมูลพื้นที่
            $table->text('rental_equipment')->nullable(); // อุปกรณ์เช่า
            $table->boolean('is_active')->default(true); // สถานะการใช้งาน
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
