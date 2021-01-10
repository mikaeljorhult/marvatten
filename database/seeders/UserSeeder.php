<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (app()->environment('local')) {
            User::factory()->create([
                'email' => 'mjr@du.se',
                'name' => 'Mikael Jorhult',
            ]);
        }

        User::factory()->count(10)->create();
    }
}
