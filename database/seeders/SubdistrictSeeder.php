<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subdistrict;

class SubdistrictSeeder extends Seeder
{
    public function run()
    {
        $subdistricts = [
            'Tempursari', 'Pronojiwo', 'Candipuro', 'Pasirian', 'Tempeh',
            'Lumajang', 'Sumbersuko', 'Tekung', 'Kunir', 'Yosowilangun',
            'Rowokangkung', 'Jatiroto', 'Randuagung', 'Sukodono', 'Padang',
            'Pasrujambe', 'Senduro', 'Gucialit', 'Kedungjajang', 'Klakah',
            'Ranuyoso'
        ];

        foreach ($subdistricts as $subdistrict) {
            Subdistrict::create([
                'name' => $subdistrict
            ]);
        }
    }
}
