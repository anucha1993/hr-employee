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
        Schema::create('employee', function (Blueprint $table) {
            $table->id();
            $table->string('emp_name');
            $table->string('emp_phone')->nullable();
            $table->unsignedBigInteger('emp_recruiter_id')->nullable();
            $table->string('emp_code')->nullable();
            $table->string('emp_department')->nullable();
            $table->string('emp_gender')->nullable();
            $table->date('emp_birthdate')->nullable();
            $table->string('emp_idcard', 13);
            $table->unsignedBigInteger('emp_education')->nullable();
            $table->unsignedBigInteger('emp_factory_id')->nullable();
            $table->text('emp_address_current')->nullable();
            $table->text('emp_address_register')->nullable();
            $table->date('emp_start_date')->nullable();
            $table->unsignedBigInteger('emp_medical_right')->nullable();
            $table->string('emp_contract_type')->default('สัญญาระยะยาว');
            $table->date('emp_contract_start')->nullable();
            $table->date('emp_contract_end')->nullable();
            $table->date('emp_resign_date')->nullable();
            $table->text('emp_resign_reason')->nullable();
            $table->unsignedBigInteger('emp_status')->nullable();
            $table->json('emp_emergency_contacts')->nullable();
            $table->json('emp_files')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee');
    }
};
