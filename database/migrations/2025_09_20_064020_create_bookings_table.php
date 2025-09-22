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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_id')->unique(); // รหัสการจอง
            $table->foreignId('trip_id')->constrained()->onDelete('cascade'); // รหัสทริป
            $table->foreignId('trip_schedule_id')->constrained()->onDelete('cascade'); // รหัสตารางเวลา
            $table->string('customer_name'); // ชื่อลูกค้า
            $table->string('customer_phone'); // เบอร์โทรลูกค้า
            $table->string('customer_email'); // อีเมลลูกค้า
            $table->string('customer_line_id')->nullable(); // Line ID ลูกค้า
            $table->integer('guests'); // จำนวนผู้เข้าร่วม
            $table->text('notes')->nullable(); // หมายเหตุ
            $table->decimal('total_price', 10, 2); // ราคารวม
            $table->datetime('booking_date'); // วันที่จอง
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending'); // สถานะการจอง
            $table->string('source')->default('web_booking'); // แหล่งที่มาของการจอง
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
