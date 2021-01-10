<?php

namespace Database\Seeders;

use App\Models\Workstation;
use Illuminate\Database\Seeder;

class WorkstationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Workstation::factory()->count(25)->create();
    }
}
