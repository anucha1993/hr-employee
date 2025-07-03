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
        Schema::table('employee', function (Blueprint $table) {
            // Add new address fields for current address
            $table->unsignedBigInteger('current_province_id')->nullable()->after('emp_address_current');
            $table->string('current_province_code')->nullable()->after('current_province_id');
            $table->unsignedBigInteger('current_amphur_id')->nullable()->after('current_province_code');
            $table->string('current_amphur_code')->nullable()->after('current_amphur_id');
            $table->unsignedBigInteger('current_district_id')->nullable()->after('current_amphur_code');
            $table->string('current_district_code')->nullable()->after('current_district_id');
            $table->string('current_zipcode', 5)->nullable()->after('current_district_code');
            $table->text('current_address_details')->nullable()->after('current_zipcode');
            
            // Add new address fields for registered address
            $table->unsignedBigInteger('registered_province_id')->nullable()->after('emp_address_register');
            $table->string('registered_province_code')->nullable()->after('registered_province_id');
            $table->unsignedBigInteger('registered_amphur_id')->nullable()->after('registered_province_code');
            $table->string('registered_amphur_code')->nullable()->after('registered_amphur_id');
            $table->unsignedBigInteger('registered_district_id')->nullable()->after('registered_amphur_code');
            $table->string('registered_district_code')->nullable()->after('registered_district_id');
            $table->string('registered_zipcode', 5)->nullable()->after('registered_district_code');
            $table->text('registered_address_details')->nullable()->after('registered_zipcode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee', function (Blueprint $table) {
            // Remove current address fields
            $table->dropColumn([
                'current_province_id',
                'current_province_code',
                'current_amphur_id',
                'current_amphur_code',
                'current_district_id',
                'current_district_code',
                'current_zipcode',
                'current_address_details'
            ]);
            
            // Remove registered address fields
            $table->dropColumn([
                'registered_province_id',
                'registered_province_code',
                'registered_amphur_id',
                'registered_amphur_code',
                'registered_district_id',
                'registered_district_code',
                'registered_zipcode',
                'registered_address_details'
            ]);
        });
    }
};
