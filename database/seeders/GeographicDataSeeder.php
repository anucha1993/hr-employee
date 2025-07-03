<?php

namespace Database\Seeders;

use App\Models\Geo\Amphure;
use App\Models\Geo\District;
use App\Models\Geo\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class GeographicDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // SQL script สำหรับนำเข้าข้อมูลจังหวัด อำเภอ และตำบล
        $provinces_path = base_path('database/sql/provinces.sql');
        $amphures_path = base_path('database/sql/amphures.sql');
        $districts_path = base_path('database/sql/districts.sql');
        
        // ตรวจสอบว่าไฟล์มีอยู่หรือไม่
        if (File::exists($provinces_path) && File::exists($amphures_path) && File::exists($districts_path)) {
            // Disable foreign key checks เพื่อความเร็วในการ seed
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // ล้างข้อมูลเดิมออก
            Province::truncate();
            Amphure::truncate();
            District::truncate();
            
            // นำเข้าข้อมูลจาก SQL file
            $this->command->info('Importing provinces data...');
            DB::unprepared(File::get($provinces_path));
            
            $this->command->info('Importing amphures data...');
            DB::unprepared(File::get($amphures_path));
            
            $this->command->info('Importing districts data...');
            DB::unprepared(File::get($districts_path));
            
            // Enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            $this->command->info('Geographic data imported successfully!');
        } else {
            // หากไม่มีไฟล์ SQL สำหรับนำเข้าข้อมูล
            $this->command->error('SQL files not found. Please place the SQL files in database/sql directory.');
            $this->command->info('Required files:');
            $this->command->info('- database/sql/provinces.sql');
            $this->command->info('- database/sql/amphures.sql');
            $this->command->info('- database/sql/districts.sql');
        }
    }
}
