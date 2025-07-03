<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GlobalSetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // สร้างชุดข้อมูลสถานะลูกค้า
        $customerStatusSet = \App\Models\globalsets\GlobalSetModel::create([
            'name' => 'สถานะลูกค้า',
            'description' => 'สถานะของลูกค้า',
        ]);
        
        // เพิ่มค่าสำหรับสถานะลูกค้า
        $customerStatusValues = [
            ['value' => 'ลูกค้าใหม่', 'status' => 'Enable', 'sort_order' => 1],
            ['value' => 'ลูกค้าปัจจุบัน', 'status' => 'Enable', 'sort_order' => 2],
            ['value' => 'ลูกค้าเก่า', 'status' => 'Enable', 'sort_order' => 3],
            ['value' => 'ลูกค้าที่หยุดใช้บริการ', 'status' => 'Enable', 'sort_order' => 4],
        ];
        
        foreach ($customerStatusValues as $value) {
            $customerStatusSet->values()->create($value);
        }
        
        // สร้างชุดข้อมูลสาขา
        $branchSet = \App\Models\globalsets\GlobalSetModel::create([
            'name' => 'สาขา',
            'description' => 'สาขาของบริษัท',
        ]);
        
        // เพิ่มค่าสำหรับสาขา
        $branchValues = [
            ['value' => 'สำนักงานใหญ่', 'status' => 'Enable', 'sort_order' => 1],
            ['value' => 'สาขากรุงเทพฯ', 'status' => 'Enable', 'sort_order' => 2],
            ['value' => 'สาขาเชียงใหม่', 'status' => 'Enable', 'sort_order' => 3],
            ['value' => 'สาขาขอนแก่น', 'status' => 'Enable', 'sort_order' => 4],
            ['value' => 'สาขาภูเก็ต', 'status' => 'Enable', 'sort_order' => 5],
        ];
        
        foreach ($branchValues as $value) {
            $branchSet->values()->create($value);
        }
        
        // สร้างชุดข้อมูลสถานะพนักงาน
        $employeeStatusSet = \App\Models\globalsets\GlobalSetModel::create([
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
