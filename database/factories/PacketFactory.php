<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Packet>
 */
class PacketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'sn' => 'rdt002',
            'counter' => fake()->unique()->numberBetween(int1: 0, int2: 1000),
            'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
            'latitude' => fake()->latitude(min: 35, max: 38),
            'longitude' => fake()->longitude(min: 21, max: 24),
            'altitude' => fake()->numberBetween(int1: 0, int2: 500),
            'speed' => fake()->numberBetween(int1: 0, int2: 200),
            'course' => fake()->numberBetween(int1: 0, int2: 200),
            'satellites' => fake()->numberBetween(int1: 0, int2: 50),
            'accelerometer' => null,
            'service1pid00' => null,
            'pids' => null,
            'dtc_status' => 'NO DTCs',
            'crc' => fake()->numberBetween(int1: 0, int2: 1000)
        ];
    }

}
