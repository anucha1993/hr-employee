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
       Schema::create('customers', function (Blueprint $table) {
    $table->id();
    $table->string('customer_name');
    $table->string('customer_taxid', 13);
    $table->string('customer_branch', 13)->nullable();
    $table->string('customer_address_number')->nullable();
    $table->string('customer_address_district')->nullable();
    $table->string('customer_address_amphur')->nullable();
    $table->string('customer_address_province')->nullable();
    $table->string('customer_address_zipcode')->nullable();
    $table->json('customer_files')->nullable(); // ✅ กรณีเก็บหลายไฟล์
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
