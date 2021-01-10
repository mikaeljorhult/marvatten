<?php

namespace Database\Factories;

use App\Models\NetworkAdapter;
use App\Models\Workstation;
use Illuminate\Database\Eloquent\Factories\Factory;

class NetworkAdapterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = NetworkAdapter::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'           => $this->faker->numerify('Ethernet ###'),
            'mac_address'    => $this->faker->macAddress(),
            'workstation_id' => Workstation::factory()
        ];
    }
}
