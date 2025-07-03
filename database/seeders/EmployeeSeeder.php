<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employees\EmployeeModel;
use App\Models\Geo\Province;
use App\Models\Geo\Amphure;
use App\Models\Geo\District;
use App\Models\customers\CustomerModel;
use App\Models\globalsets\GlobalSetModel;
use App\Models\globalsets\GlobalSetValueModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // เช็คข้อมูลที่จำเป็น
        $provinces = Province::take(5)->get();
        if ($provinces->isEmpty()) {
            $this->command->error('ไม่มีข้อมูลจังหวัด กรุณา migrate และ seed ข้อมูลจังหวัด อำเภอ ตำบลก่อน');
            return;
        }

        // เก็บค่า GlobalSet ที่จำเป็นสำหรับการสร้างพนักงาน
        $educationOptions = GlobalSetValueModel::where('global_set_id', GlobalSetModel::where('name', 'EDUCATION')->first()->id ?? 0)->pluck('id')->toArray();
        $statusOptions = GlobalSetValueModel::where('global_set_id', GlobalSetModel::where('name', 'EMP_STATUS')->first()->id ?? 0)->pluck('id')->toArray();
        $recruiterOptions = GlobalSetValueModel::where('global_set_id', GlobalSetModel::where('name', 'RECRUITER')->first()->id ?? 0)->pluck('id')->toArray();
        $medicalOptions = GlobalSetValueModel::where('global_set_id', GlobalSetModel::where('name', 'MEDICAL_RIGHT')->first()->id ?? 0)->pluck('id')->toArray();
        $factories = CustomerModel::where('customer_status', '1')->pluck('id')->toArray();

        if (empty($educationOptions) || empty($statusOptions) || empty($recruiterOptions) || empty($medicalOptions) || empty($factories)) {
            $this->command->error('ไม่มีข้อมูลจำเป็นสำหรับการสร้างพนักงาน กรุณาสร้างข้อมูล GlobalSet หรือ Customer ก่อน');
            return;
        }

        // ข้อมูลตัวอย่างพนักงาน 20 คน
        $employees = [
            // 1
            [
                'emp_name' => 'สมชาย ใจดี',
                'emp_phone' => '0812345678',
                'emp_idcard' => '1234567890123',
                'emp_gender' => 'ชาย',
                'emp_birthdate' => '1990-05-15',
                'emp_department' => 'ฝ่ายผลิต',
                'emp_code' => 'EMP001',
            ],
            // 2
            [
                'emp_name' => 'สมหญิง มีสุข',
                'emp_phone' => '0823456789',
                'emp_idcard' => '2345678901234',
                'emp_gender' => 'หญิง',
                'emp_birthdate' => '1992-03-20',
                'emp_department' => 'ฝ่ายบัญชี',
                'emp_code' => 'EMP002',
            ],
            // 3
            [
                'emp_name' => 'วิชัย สุขสันต์',
                'emp_phone' => '0834567890',
                'emp_idcard' => '3456789012345',
                'emp_gender' => 'ชาย',
                'emp_birthdate' => '1988-10-10',
                'emp_department' => 'ฝ่ายผลิต',
                'emp_code' => 'EMP003',
            ],
            // 4
            [
                'emp_name' => 'นงนุช สมใจ',
                'emp_phone' => '0845678901',
                'emp_idcard' => '4567890123456',
                'emp_gender' => 'หญิง',
                'emp_birthdate' => '1995-07-25',
                'emp_department' => 'ฝ่ายบุคคล',
                'emp_code' => 'EMP004',
            ],
            // 5
            [
                'emp_name' => 'สมภพ อุดมศักดิ์',
                'emp_phone' => '0856789012',
                'emp_idcard' => '5678901234567',
                'emp_gender' => 'ชาย',
                'emp_birthdate' => '1985-12-05',
                'emp_department' => 'ฝ่ายผลิต',
                'emp_code' => 'EMP005',
            ],
            // 6
            [
                'emp_name' => 'จิรา ประเสริฐ',
                'emp_phone' => '0867890123',
                'emp_idcard' => '6789012345678',
                'emp_gender' => 'หญิง',
                'emp_birthdate' => '1993-09-18',
                'emp_department' => 'ฝ่ายการตลาด',
                'emp_code' => 'EMP006',
            ],
            // 7
            [
                'emp_name' => 'ประสิทธิ์ รัตนกูล',
                'emp_phone' => '0878901234',
                'emp_idcard' => '7890123456789',
                'emp_gender' => 'ชาย',
                'emp_birthdate' => '1982-04-30',
                'emp_department' => 'ฝ่ายผลิต',
                'emp_code' => 'EMP007',
            ],
            // 8
            [
                'emp_name' => 'ศิริพร พงษ์พันธ์',
                'emp_phone' => '0889012345',
                'emp_idcard' => '8901234567890',
                'emp_gender' => 'หญิง',
                'emp_birthdate' => '1991-02-14',
                'emp_department' => 'ฝ่ายผลิต',
                'emp_code' => 'EMP008',
            ],
            // 9
            [
                'emp_name' => 'สุรพล วิภาวี',
                'emp_phone' => '0890123456',
                'emp_idcard' => '9012345678901',
                'emp_gender' => 'ชาย',
                'emp_birthdate' => '1987-08-08',
                'emp_department' => 'ฝ่ายขนส่ง',
                'emp_code' => 'EMP009',
            ],
            // 10
            [
                'emp_name' => 'ปรียา มาลากุล',
                'emp_phone' => '0901234567',
                'emp_idcard' => '0123456789012',
                'emp_gender' => 'หญิง',
                'emp_birthdate' => '1994-11-22',
                'emp_department' => 'ฝ่ายผลิต',
                'emp_code' => 'EMP010',
            ],
            // 11
            [
                'emp_name' => 'นิรุตติ์ ศรีธาดา',
                'emp_phone' => '0912345678',
                'emp_idcard' => '1122334455667',
                'emp_gender' => 'ชาย',
                'emp_birthdate' => '1986-06-03',
                'emp_department' => 'ฝ่ายผลิต',
                'emp_code' => 'EMP011',
            ],
            // 12
            [
                'emp_name' => 'วรรณา ภัทรสิริ',
                'emp_phone' => '0923456789',
                'emp_idcard' => '2233445566778',
                'emp_gender' => 'หญิง',
                'emp_birthdate' => '1996-01-10',
                'emp_department' => 'ฝ่ายบัญชี',
                'emp_code' => 'EMP012',
            ],
            // 13
            [
                'emp_name' => 'อนันต์ สุขสวัสดิ์',
                'emp_phone' => '0934567890',
                'emp_idcard' => '3344556677889',
                'emp_gender' => 'ชาย',
                'emp_birthdate' => '1989-03-28',
                'emp_department' => 'ฝ่ายผลิต',
                'emp_code' => 'EMP013',
            ],
            // 14
            [
                'emp_name' => 'ธัญญา สิริกุล',
                'emp_phone' => '0945678901',
                'emp_idcard' => '4455667788990',
                'emp_gender' => 'หญิง',
                'emp_birthdate' => '1997-05-05',
                'emp_department' => 'ฝ่ายผลิต',
                'emp_code' => 'EMP014',
            ],
            // 15
            [
                'emp_name' => 'สมศักดิ์ วิศวกร',
                'emp_phone' => '0956789012',
                'emp_idcard' => '5566778899001',
                'emp_gender' => 'ชาย',
                'emp_birthdate' => '1984-07-17',
                'emp_department' => 'ฝ่ายผลิต',
                'emp_code' => 'EMP015',
            ],
            // 16
            [
                'emp_name' => 'พรพิมล สุวรรณศิลป์',
                'emp_phone' => '0967890123',
                'emp_idcard' => '6677889900112',
                'emp_gender' => 'หญิง',
                'emp_birthdate' => '1990-09-29',
                'emp_department' => 'ฝ่ายการเงิน',
                'emp_code' => 'EMP016',
            ],
            // 17
            [
                'emp_name' => 'ธีระพงศ์ ชัยวัฒน์',
                'emp_phone' => '0978901234',
                'emp_idcard' => '7788990011223',
                'emp_gender' => 'ชาย',
                'emp_birthdate' => '1983-11-11',
                'emp_department' => 'ฝ่ายผลิต',
                'emp_code' => 'EMP017',
            ],
            // 18
            [
                'emp_name' => 'นันทนา ไพศาล',
                'emp_phone' => '0989012345',
                'emp_idcard' => '8899001122334',
                'emp_gender' => 'หญิง',
                'emp_birthdate' => '1995-02-02',
                'emp_department' => 'ฝ่ายผลิต',
                'emp_code' => 'EMP018',
            ],
            // 19
            [
                'emp_name' => 'วีรพงษ์ ศุภกิจ',
                'emp_phone' => '0990123456',
                'emp_idcard' => '9900112233445',
                'emp_gender' => 'ชาย',
                'emp_birthdate' => '1988-04-14',
                'emp_department' => 'ฝ่ายผลิต',
                'emp_code' => 'EMP019',
            ],
            // 20
            [
                'emp_name' => 'มาลี วิภาดา',
                'emp_phone' => '0991234567',
                'emp_idcard' => '0011223344556',
                'emp_gender' => 'หญิง',
                'emp_birthdate' => '1993-12-20',
                'emp_department' => 'ฝ่ายบุคคล',
                'emp_code' => 'EMP020',
            ],
        ];

        // ล้างข้อมูลพนักงานเดิมถ้ามี
        EmployeeModel::truncate();

        $this->command->info('เริ่มสร้างข้อมูลพนักงานตัวอย่าง 20 คน...');

        // สร้างข้อมูลพนักงานทั้ง 20 คน
        foreach ($employees as $employee) {
            // เลือกจังหวัดสุ่ม
            $province = $provinces->random();
            $amphures = Amphure::where('province_code', $province->province_code)->take(5)->get();
            if ($amphures->isEmpty()) continue;
            $amphure = $amphures->random();
            $districts = District::where('amphur_code', $amphure->amphur_code)->take(5)->get();
            if ($districts->isEmpty()) continue;
            $district = $districts->random();

            // สุ่มข้อมูลจาก GlobalSet และ Customer
            $educationId = !empty($educationOptions) ? $educationOptions[array_rand($educationOptions)] : null;
            $statusId = !empty($statusOptions) ? $statusOptions[array_rand($statusOptions)] : null;
            $recruiterId = !empty($recruiterOptions) ? $recruiterOptions[array_rand($recruiterOptions)] : null;
            $medicalId = !empty($medicalOptions) ? $medicalOptions[array_rand($medicalOptions)] : null;
            $factoryId = !empty($factories) ? $factories[array_rand($factories)] : null;

            // กำหนดวันเริ่มงาน
            $startDate = Carbon::now()->subMonths(rand(1, 24))->format('Y-m-d');
            
            // ข้อมูลที่อยู่
            $addressDetails = 'บ้านเลขที่ ' . rand(1, 999) . ' หมู่ ' . rand(1, 20);
            
            // ข้อมูลผู้ติดต่อฉุกเฉิน
            $emergencyContacts = [
                [
                    'name' => 'ญาติคนที่ 1 ของ' . explode(' ', $employee['emp_name'])[0],
                    'phone' => '08' . rand(10000000, 99999999),
                    'relation' => ['พ่อ', 'แม่', 'พี่', 'น้อง', 'ญาติ'][rand(0, 4)]
                ],
                [
                    'name' => 'ญาติคนที่ 2 ของ' . explode(' ', $employee['emp_name'])[0],
                    'phone' => '08' . rand(10000000, 99999999),
                    'relation' => ['พ่อ', 'แม่', 'พี่', 'น้อง', 'ญาติ'][rand(0, 4)]
                ]
            ];

            // สร้างข้อมูลพนักงาน
            EmployeeModel::create([
                'emp_name' => $employee['emp_name'],
                'emp_phone' => $employee['emp_phone'],
                'emp_recruiter_id' => $recruiterId,
                'emp_code' => $employee['emp_code'],
                'emp_department' => $employee['emp_department'],
                'emp_gender' => $employee['emp_gender'],
                'emp_birthdate' => $employee['emp_birthdate'],
                'emp_idcard' => $employee['emp_idcard'],
                'emp_education' => $educationId,
                'emp_factory_id' => $factoryId,
                
                // ข้อมูลที่อยู่ปัจจุบัน
                'current_province_id' => $province->id,
                'current_province_code' => $province->province_code,
                'current_amphur_id' => $amphure->id,
                'current_amphur_code' => $amphure->amphur_code,
                'current_district_id' => $district->id,
                'current_district_code' => $district->district_code,
                'current_zipcode' => $district->zipcode,
                'current_address_details' => $addressDetails,
                
                // ข้อมูลที่อยู่ตามทะเบียนบ้าน (ใช้ค่าเดียวกันกับที่อยู่ปัจจุบันเพื่อความง่าย)
                'registered_province_id' => $province->id,
                'registered_province_code' => $province->province_code,
                'registered_amphur_id' => $amphure->id,
                'registered_amphur_code' => $amphure->amphur_code,
                'registered_district_id' => $district->id,
                'registered_district_code' => $district->district_code,
                'registered_zipcode' => $district->zipcode,
                'registered_address_details' => $addressDetails,
                
                // ข้อมูลการทำงาน
                'emp_start_date' => $startDate,
                'emp_medical_right' => $medicalId,
                'emp_contract_type' => rand(0, 1) ? 'สัญญาระยะยาว' : 'สัญญาระยะสั้น',
                'emp_contract_start' => $startDate,
                'emp_contract_end' => Carbon::parse($startDate)->addYears(1)->format('Y-m-d'),
                'emp_status' => $statusId,
                'emp_emergency_contacts' => $emergencyContacts,
                
                // สร้างข้อความที่อยู่แบบเต็ม
                'emp_address_current' => $addressDetails . ' ต.' . $district->district_name . ' อ.' . $amphure->amphur_name . ' จ.' . $province->province_name . ' ' . $district->zipcode,
                'emp_address_register' => $addressDetails . ' ต.' . $district->district_name . ' อ.' . $amphure->amphur_name . ' จ.' . $province->province_name . ' ' . $district->zipcode,
            ]);
        }

        $this->command->info('สร้างข้อมูลพนักงานตัวอย่างเรียบร้อยแล้ว!');
    }
}
