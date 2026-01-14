<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Geo\Province;
use App\Models\Geo\Amphure;
use App\Models\Geo\District;

echo "Checking Mae Hong Son province:\n";

$province = Province::where('province_name', 'LIKE', '%แม่ฮ่องสอน%')
    ->orWhere('province_name', 'LIKE', '%แม่ฮองสอน%')
    ->first();

if ($province) {
    echo "Province: {$province->province_name} (ID: {$province->id}, Code: {$province->province_code})\n\n";
    
    $amphures = Amphure::where('province_code', $province->province_code)->get();
    echo "Total Amphures: {$amphures->count()}\n\n";
    
    foreach ($amphures as $amphur) {
        echo "Amphur: {$amphur->amphur_name} (ID: {$amphur->id}, Code: {$amphur->amphur_code})\n";
        
        $districts = District::where('amphur_code', $amphur->amphur_code)->get();
        echo "  Districts: {$districts->count()}\n";
        
        foreach ($districts as $district) {
            echo "    - {$district->district_name} (Code: {$district->district_code}, Zip: {$district->zipcode})\n";
        }
        echo "\n";
    }
} else {
    echo "Province not found!\n";
}
