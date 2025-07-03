<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\globalsets\GlobalSetModel;

class EmployeeStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // สร้างชุดข้อมูลสถานะพนักงาน
        $employeeStatusSet = GlobalSetModel::create([
            'name' => 'EMP_STATUS',
            'description' => 'สถานะการทำงานของพนักงาน',
        ]);
        
        // เพิ่มค่าสำหรับสถานะพนักงาน
        $employeeStatusValues = [
            ['value' => 'ทำงาน', 'status' => 'Enable', 'sort_order' => 1],
            ['value' => 'ลาออก', 'status' => 'Enable', 'sort_order' => 2],
            ['value' => 'พักงาน', 'status' => 'Enable', 'sort_order' => 3],
            ['value' => 'เลิกจ้าง', 'status' => 'Enable', 'sort_order' => 4],
        ];
        
        foreach ($employeeStatusValues as $value) {
            $employeeStatusSet->values()->create($value);
        }
    }
}
