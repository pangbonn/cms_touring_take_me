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
        Schema::create('booking_terms', function (Blueprint $table) {
            $table->id();
            $table->string('term_title')->comment('หัวข้อเงื่อนไข');
            $table->text('term_content')->comment('เนื้อหาเงื่อนไข');
            $table->enum('term_category', ['booking', 'payment', 'travel', 'responsibility', 'group', 'seat_selection'])->comment('หมวดหมู่เงื่อนไข');
            $table->integer('sort_order')->default(0)->comment('ลำดับการแสดง');
            $table->boolean('is_active')->default(true)->comment('สถานะการใช้งาน');
            $table->boolean('is_required')->default(false)->comment('เป็นเงื่อนไขบังคับหรือไม่');
            $table->text('additional_info')->nullable()->comment('ข้อมูลเพิ่มเติม');
            $table->foreignId('created_by')->constrained('users')->comment('ผู้สร้างเงื่อนไข');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_terms');
    }
};
