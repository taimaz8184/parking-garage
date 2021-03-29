<?php

namespace Database\Seeders;

use App\Models\ParkingLot;
use Illuminate\Database\Seeder;

class CreateParkPlaces extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 25; $i++) {
            ParkingLot::create();
        }
    }
}
