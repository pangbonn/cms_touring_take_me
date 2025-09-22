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
        Schema::create('trip_cancellations', function (Blueprint $table) {
            $table->id();
            $table->string('trip_name')->comment('ชื่อทริป');
            $table->text('trip_description')->nullable()->comment('รายละเอียดทริป');
            $table->date('trip_date')->comment('วันที่เดินทาง');
            $table->decimal('trip_price', 10, 2)->comment('ราคาทริป');
            $table->integer('min_participants')->default(1)->comment('จำนวนผู้เข้าร่วมขั้นต่ำ');
            $table->integer('max_participants')->nullable()->comment('จำนวนผู้เข้าร่วมสูงสุด');
            $table->enum('cancellation_type', ['automatic', 'manual'])->default('manual')->comment('ประเภทการยกเลิก');
            $table->json('cancellation_conditions')->nullable()->comment('เงื่อนไขการยกเลิก (JSON)');
            $table->text('refund_policy')->nullable()->comment('นโยบายการคืนเงิน');
            $table->enum('status', ['active', 'cancelled', 'completed'])->default('active')->comment('สถานะทริป');
            $table->foreignId('created_by')->constrained('users')->comment('ผู้สร้างทริป');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_cancellations');
    }
};
