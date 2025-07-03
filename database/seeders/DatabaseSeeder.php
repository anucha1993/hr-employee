<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default admin user
        \App\Models\User::factory(1)->create([
            'name' => 'Velonic',
            'email' => 'test@test.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token' => Str::random(10),
        ]);
        
        // Create user for Anucha Yothanan
        \App\Models\User::create([
            'name' => 'Anucha Yothanan',
            'email' => 'anucha.yothanan@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('aod13364734'),
            'remember_token' => Str::random(10),
        ]);
        
        // Seed geographic data (provinces, amphures, districts)
        $this->call(GeographicDataSeeder::class);
        
        // เพิ่ม Seeder สำหรับข้อมูลทั่วไปและข้อมูลลูกค้า
        $this->call([
            GlobalSetSeeder::class,
            EmployeeStatusSeeder::class,
            CustomerSeeder::class,
            EmployeeSeeder::class,
        ]);
    }
}
