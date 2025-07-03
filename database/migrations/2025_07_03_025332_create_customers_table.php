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
            $table->string('customer_taxid')->nullable();
            $table->unsignedBigInteger('customer_branch')->nullable();
            $table->string('customer_branch_name')->nullable();
            $table->string('customer_address_number')->nullable();
            $table->string('customer_address_district')->nullable();
            $table->string('customer_address_amphur')->nullable();
            $table->string('customer_address_province')->nullable();
            $table->string('customer_address_zipcode', 5)->nullable();
            
            // ข้อมูลผู้ติดต่อ
            $table->string('customer_contact_name_1')->nullable();
            $table->string('customer_contact_phone_1')->nullable();
            $table->string('customer_contact_email_1')->nullable();
            $table->string('customer_contact_position_1')->nullable();
            $table->string('customer_contact_name_2')->nullable();
            $table->string('customer_contact_phone_2')->nullable();
            $table->string('customer_contact_email_2')->nullable();
            $table->string('customer_contact_position_2')->nullable();
            
            // ข้อมูลเพิ่มเติม
            $table->string('customer_thefirst_contact_name')->nullable();
            $table->string('customer_thefirst_contact_phone')->nullable();
            $table->string('customer_thefirst_acc_name')->nullable();
            $table->string('customer_thefirst_acc_phone')->nullable();
            $table->string('customer_thefirst_invoice_name')->nullable();
            $table->string('customer_thefirst_invoice_phone')->nullable();
            $table->text('customer_salary_cut_note')->nullable();
            $table->text('customer_salary_note')->nullable();
            $table->string('customer_clinic_name')->nullable();
            $table->decimal('customer_clinic_price', 10, 2)->nullable();
            $table->boolean('customer_cid_check')->default(false);
            $table->integer('customer_employee_total_required')->nullable();
            $table->string('customer_status')->nullable();
            
            // ไฟล์แนบ
            $table->json('customer_files')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
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
