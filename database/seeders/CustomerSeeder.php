<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ดึงข้อมูล GlobalSet Values สำหรับสาขาและสถานะลูกค้า
        $branchSet = \App\Models\globalsets\GlobalSetModel::where('name', 'สาขา')->first();
        $statusSet = \App\Models\globalsets\GlobalSetModel::where('name', 'สถานะลูกค้า')->first();
        
        if ($branchSet && $statusSet) {
            $branches = $branchSet->values->pluck('id')->toArray();
            $statuses = $statusSet->values->pluck('id')->toArray();
            
            // สร้างลูกค้าตัวอย่าง 5 ราย
            $customers = [
                [
                    'customer_name' => 'บริษัท อะเมซิ่ง เทคโนโลยี จำกัด',
                    'customer_taxid' => '0123456789012',
                    'customer_branch' => $branches[0],
                    'customer_branch_name' => 'สำนักงานใหญ่',
                    'customer_address_number' => '123/45',
                    'customer_address_district' => 'คลองเตย',
                    'customer_address_amphur' => 'คลองเตย',
                    'customer_address_province' => 'กรุงเทพมหานคร',
                    'customer_address_zipcode' => '10110',
                    'customer_contact_name_1' => 'คุณสมหวัง มุ่งมั่น',
                    'customer_contact_phone_1' => '0812345678',
                    'customer_contact_email_1' => 'somwang@amazingtech.co.th',
                    'customer_contact_position_1' => 'ผู้จัดการฝ่ายบุคคล',
                    'customer_employee_total_required' => 15,
                    'customer_status' => $statuses[1],
                    'created_by' => 1,
                ],
                [
                    'customer_name' => 'บริษัท ไทยเจริญ โลจิสติกส์ จำกัด',
                    'customer_taxid' => '0123456789034',
                    'customer_branch' => $branches[0],
                    'customer_branch_name' => 'สำนักงานใหญ่',
                    'customer_address_number' => '99/2',
                    'customer_address_district' => 'บางนา',
                    'customer_address_amphur' => 'บางนา',
                    'customer_address_province' => 'กรุงเทพมหานคร',
                    'customer_address_zipcode' => '10260',
                    'customer_contact_name_1' => 'คุณมั่นคง ทรัพย์อนันต์',
                    'customer_contact_phone_1' => '0897654321',
                    'customer_contact_email_1' => 'mankong@thaicharoen.co.th',
                    'customer_contact_position_1' => 'กรรมการผู้จัดการ',
                    'customer_employee_total_required' => 8,
                    'customer_status' => $statuses[0],
                    'created_by' => 1,
                ],
                [
                    'customer_name' => 'ห้างหุ้นส่วนจำกัด พันธมิตรก่อสร้าง',
                    'customer_taxid' => '0123456789056',
                    'customer_branch' => $branches[0],
                    'customer_branch_name' => 'สำนักงานใหญ่',
                    'customer_address_number' => '56/7',
                    'customer_address_district' => 'ห้วยขวาง',
                    'customer_address_amphur' => 'ห้วยขวาง',
                    'customer_address_province' => 'กรุงเทพมหานคร',
                    'customer_address_zipcode' => '10310',
                    'customer_contact_name_1' => 'คุณสุดา รักดี',
                    'customer_contact_phone_1' => '0645678901',
                    'customer_contact_email_1' => 'suda@pantamit.co.th',
                    'customer_contact_position_1' => 'ฝ่ายบัญชี',
                    'customer_employee_total_required' => 25,
                    'customer_status' => $statuses[1],
                    'created_by' => 1,
                ]
            ];
            
            foreach ($customers as $customerData) {
                $customer = \App\Models\customers\CustomerModel::create($customerData);
                
                // สร้างสัญญาตัวอย่างสำหรับลูกค้า
                $startDate = now()->subMonth(rand(1, 6));
                $endDate = $startDate->copy()->addYear();
                
                $customer->contracts()->create([
                    'contract_sort' => 1,
                    'contract_number' => 'CT-' . date('Ymd') . '-' . str_pad($customer->id, 4, '0', STR_PAD_LEFT),
                    'contract_start_date' => $startDate,
                    'contract_end_date' => $endDate,
                ]);
            }
        }
    }
}
