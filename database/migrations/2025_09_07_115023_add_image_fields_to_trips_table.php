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
        Schema::table('trips', function (Blueprint $table) {
            // เพิ่มฟิลด์รูปภาพ
            $table->string('cover_image')->nullable()->after('image'); // รูป Cover
            $table->json('sample_images')->nullable()->after('cover_image'); // รูปตัวอย่าง 4 รูป (เก็บเป็น JSON array)
            
            // เพิ่มฟิลด์สำหรับตั้งค่าปิด/เปิดฟิลด์ต่างๆ
            $table->boolean('show_itinerary')->default(true)->after('rental_equipment');
            $table->boolean('show_total_cost')->default(true)->after('show_itinerary');
            $table->boolean('show_personal_items')->default(true)->after('show_total_cost');
            $table->boolean('show_rental_equipment')->default(true)->after('show_personal_items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn([
                'cover_image',
                'sample_images',
                'show_itinerary',
                'show_total_cost',
                'show_personal_items',
                'show_rental_equipment'
            ]);
        });
    }
};
