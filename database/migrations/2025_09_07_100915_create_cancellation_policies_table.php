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
        Schema::create('cancellation_policies', function (Blueprint $table) {
            $table->id();
            $table->string('policy_name')->comment('ชื่อนโยบายการยกเลิก');
            $table->text('policy_description')->nullable()->comment('รายละเอียดนโยบาย');
            $table->enum('policy_type', ['standard', 'force_majeure', 'location_specific'])->default('standard')->comment('ประเภทนโยบาย');
            $table->json('cancellation_conditions')->comment('เงื่อนไขการยกเลิก (JSON)');
            $table->text('force_majeure_conditions')->nullable()->comment('เงื่อนไขเหตุสุดวิสัย');
            $table->string('applicable_locations')->nullable()->comment('สถานที่ที่ใช้บังคับ (JSON)');
            $table->boolean('is_active')->default(true)->comment('สถานะการใช้งาน');
            $table->boolean('is_default')->default(false)->comment('เป็นนโยบายเริ่มต้นหรือไม่');
            $table->integer('priority')->default(0)->comment('ลำดับความสำคัญ');
            $table->foreignId('created_by')->constrained('users')->comment('ผู้สร้างนโยบาย');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cancellation_policies');
    }
};
