<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SurfaceArea;
use App\Models\Subdistrict;
use App\Models\CommodityItem;
use Carbon\Carbon;

class SurfaceSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua subdistrik
        $subdistricts = Subdistrict::all();

        // Ambil semua commodity item
        $commodityItems = CommodityItem::all();

        // Loop pada setiap subdistrik
        foreach ($commodityItems as $commodityItem) {
            // Tentukan tanggal acak untuk commodity item ini
            $date = $this->randomDate();

            // Loop pada setiap subdistrik
            foreach ($subdistricts as $subdistrict) {
                // Ambil ID subdistrik
                $subdistrictId = $subdistrict->id;

                // Tambahkan data ke dalam tabel surface_area
                SurfaceArea::create([
                    'commodity_item_id' => $commodityItem->id,
                    'subdistrict_id' => $subdistrictId,
                    'plant' => rand(1, 10) + (rand(0, 9) / 10), // Nilai acak antara 1 dan 10 dengan desimal
                    'harvest' => rand(1, 10) + (rand(0, 9) / 10), // Nilai acak antara 1 dan 10 dengan desimal
                    'production' => rand(1, 10) + (rand(0, 9) / 10), // Nilai acak antara 1 dan 10 dengan desimal
                    'productivity' => rand(1, 10) + (rand(0, 9) / 10), // Nilai acak antara 1 dan 10 dengan desimal
                    'date' => $date,
                ]);
            }
        }
    }

    private function randomDate()
    {
        $start = Carbon::create(2023, 1, 1);
        $end = Carbon::create(2024, 3, 1);
        return $start->addMonths(rand(0, $end->diffInMonths($start)))->toDateString();
    }
}
