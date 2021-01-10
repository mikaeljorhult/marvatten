<?php

namespace Database\Factories;

use App\Models\Workstation;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkstationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Workstation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'   => $this->faker->city,
            'serial' => $this->faker->uuid,
        ];
    }

    /**
     * Add meta fields to workstation.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withMeta()
    {
        return $this
            ->state(function (array $attributes) {
                return [];
            })
            ->afterCreating(function (Workstation $workstation) {
                $workstation->metas()->create([
                    'label' => 'Test Label',
                    'value' => 'Test Value',
                ]);
            });
    }
}
