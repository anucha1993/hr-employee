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
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->string('district_name');
            $table->string('district_code', 6)->nullable();
            $table->string('amphur_code', 4)->nullable();
            $table->integer('province_code')->nullable();
            $table->string('zipcode', 5)->nullable();
            $table->timestamps();
            
            // เพิ่ม indexes สำหรับช่องที่ใช้ในการค้นหาบ่อย
            $table->index('amphur_code');
            $table->index('province_code');
            $table->index('zipcode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};
