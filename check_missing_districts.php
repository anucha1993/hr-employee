<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Geo\Province;
use App\Models\Geo\Amphure;
use App\Models\Geo\District;

echo "Checking provinces with missing district data:\n\n";

$provinces = Province::orderBy('province_name')->get();

foreach ($provinces as $province) {
    $amphures = Amphure::where('province_code', $province->province_code)->get();
    
    $amphuremissingDistricts = [];
    
    foreach ($amphures as $amphur) {
        $districtsCount = District::where('amphur_code', $amphur->amphur_code)->count();
        if ($districtsCount == 0) {
            $amphuresWithMissingDistricts[] = $amphur->amphur_name;
        }
    }
    
    if (!empty($amphuresWithMissingDistricts)) {
        echo "จังหวัด: {$province->province_name} (Code: {$province->province_code})\n";
        echo "  อำเภอที่ไม่มีข้อมูลตำบล: " . count($amphuresWithMissingDistricts) . " / {$amphures->count()} อำเภอ\n";
        foreach ($amphuresWithMissingDistricts as $amphurName) {
            echo "    - {$amphurName}\n";
        }
        echo "\n";
    }
}
