<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Geo\Province;
use App\Models\Geo\Amphure;
use App\Models\Geo\District;

echo "Database Summary:\n\n";

$totalProvinces = Province::count();
$totalAmphures = Amphure::count();
$totalDistricts = District::count();

echo "Total Provinces: {$totalProvinces}\n";
echo "Total Amphures: {$totalAmphures}\n";
echo "Total Districts: {$totalDistricts}\n\n";

// Check distinct province codes in districts table
$distinctProvincesInDistricts = District::select('province_code')->distinct()->count();
echo "Provinces with district data: {$distinctProvincesInDistricts}\n\n";

// List provinces that have district data
echo "Provinces with district data:\n";
$provincesWithData = District::select('province_code')->distinct()->get();
foreach ($provincesWithData as $provinceData) {
    $province = Province::where('province_code', $provinceData->province_code)->first();
    if ($province) {
        $districtCount = District::where('province_code', $provinceData->province_code)->count();
        echo "  - {$province->province_name} (Code: {$provinceData->province_code}): {$districtCount} districts\n";
    }
}
