<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Geo\Province;
use App\Models\Geo\Amphure;
use App\Models\Geo\District;

echo "Checking amphur_code relationship:\n\n";

// Get Mae Hong Son amphures
$maeHongSonProvince = Province::where('province_code', 58)->first();
if ($maeHongSonProvince) {
    echo "Mae Hong Son Province:\n";
    echo "  ID: {$maeHongSonProvince->id}\n";
    echo "  Code: {$maeHongSonProvince->province_code}\n\n";
    
    $amphures = Amphure::where('province_code', 58)->get();
    echo "Amphures in Mae Hong Son: {$amphures->count()}\n\n";
    
    foreach ($amphures as $amphur) {
        echo "Amphur: {$amphur->amphur_name}\n";
        echo "  amphur_code (from amphures table): {$amphur->amphur_code}\n";
        
        // Check if there are any districts
        $districts = District::where('amphur_code', $amphur->amphur_code)->get();
        echo "  Districts found: {$districts->count()}\n";
        
        if ($districts->count() > 0) {
            echo "  Sample districts:\n";
            foreach ($districts->take(3) as $district) {
                echo "    - {$district->district_name} (code: {$district->district_code})\n";
            }
        }
        echo "\n";
    }
    
    // Now check what amphur codes exist in districts table for province code 58
    echo "\nChecking districts table for province_code 58:\n";
    $districtsForProvince = District::where('province_code', 58)->get();
    echo "Districts with province_code = 58: {$districtsForProvince->count()}\n";
    
    if ($districtsForProvince->count() > 0) {
        $uniqueAmphurCodes = $districtsForProvince->pluck('amphur_code')->unique();
        echo "Unique amphur_codes in districts: " . $uniqueAmphurCodes->count() . "\n";
        echo "Amphur codes: " . $uniqueAmphurCodes->implode(', ') . "\n";
    }
}
