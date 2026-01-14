<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Geo\Province;
use App\Models\Geo\Amphure;
use App\Models\Geo\District;

echo "Checking amphur codes and districts for Mae Hong Son:\n\n";

$province = Province::where('province_name', 'LIKE', '%แม่ฮ่องสอน%')->first();

if ($province) {
    echo "Province: {$province->province_name} (Code: {$province->province_code})\n\n";
    
    $amphures = Amphure::where('province_code', $province->province_code)->get();
    
    foreach ($amphures as $amphur) {
        echo "Amphur: {$amphur->amphur_name}\n";
        echo "  ID: {$amphur->id}\n";
        echo "  amphur_code: '{$amphur->amphur_code}'\n";
        echo "  province_code: '{$amphur->province_code}'\n";
        
        // Check districts with exact match
        $districts = District::where('amphur_code', $amphur->amphur_code)->get();
        echo "  Districts (exact match): {$districts->count()}\n";
        
        // Check if there are any districts with similar codes
        $similarDistricts = District::where('amphur_code', 'LIKE', $amphur->amphur_code . '%')->get();
        echo "  Districts (similar match): {$similarDistricts->count()}\n";
        
        // Show sample district codes
        $sampleDistricts = District::select('amphur_code')->distinct()->limit(5)->get();
        echo "  Sample district amphur_codes in DB: ";
        foreach ($sampleDistricts as $d) {
            echo "'{$d->amphur_code}', ";
        }
        echo "\n\n";
        
        break; // Just check first amphur
    }
    
    // Check data types
    echo "\nChecking data structure:\n";
    $sampleAmphur = $amphures->first();
    echo "Amphur code type: " . gettype($sampleAmphur->amphur_code) . "\n";
    echo "Amphur code value: '{$sampleAmphur->amphur_code}'\n";
    echo "Amphur code length: " . strlen($sampleAmphur->amphur_code) . "\n";
    
    $sampleDistrict = District::first();
    if ($sampleDistrict) {
        echo "District code type: " . gettype($sampleDistrict->amphur_code) . "\n";
        echo "District code value: '{$sampleDistrict->amphur_code}'\n";
        echo "District code length: " . strlen($sampleDistrict->amphur_code) . "\n";
    }
}
