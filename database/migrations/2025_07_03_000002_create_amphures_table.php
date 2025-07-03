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
        Schema::create('amphures', function (Blueprint $table) {
            $table->id();
            $table->string('amphur_name');
            $table->string('amphur_code', 4)->nullable();
            $table->integer('province_code')->nullable();
            $table->timestamps();
            
            // เพิ่ม index สำหรับ province_code เพื่อความเร็วในการค้นหา
            $table->index('province_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amphures');
    }
};
