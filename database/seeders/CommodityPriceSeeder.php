<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CommodityPrice;
use App\Models\CommodityItem;
use Carbon\Carbon;

class CommodityPriceSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua data komoditas
        $commodityItems = CommodityItem::all();

        // Daftar tanggal yang akan digunakan dalam seeder
        $dates = $this->generateRandomDates(30); // Menghasilkan 30 tanggal acak

        // Loop melalui semua komoditas
        foreach ($commodityItems as $commodityItem) {
            // Ambil harga acak untuk petani dan pedagang
            $harga_petani = rand(2000, 10000); // Harga petani antara 2000 dan 10000
            $harga_pedagang = $harga_petani + rand(500, 2000); // Harga pedagang ditambahkan dengan nilai acak antara 500 dan 2000

            // Loop melalui semua tanggal
            foreach ($dates as $date) {
                // Tambahkan data harga komoditas ke dalam tabel
                CommodityPrice::create([
                    'commodity_item_id' => $commodityItem->id,
                    'farmer_price' => $harga_petani,
                    'seller_price' => $harga_pedagang,
                    'date_input' => $date,
                ]);
            }
        }
    }

    // Fungsi untuk menghasilkan tanggal acak
    private function generateRandomDates($count)
    {
        $dates = [];
        $startDate = Carbon::now()->subDays(365); // Mengambil tanggal dari setahun yang lalu
        $endDate = Carbon::now(); // Mengambil tanggal saat ini

        for ($i = 0; $i < $count; $i++) {
            $randomDate = Carbon::createFromTimestamp(rand($startDate->timestamp, $endDate->timestamp));
            $dates[] = $randomDate->toDateString();
        }

        return $dates;
    }
}
