<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'event_name' => $this->faker->name(),
            'start' => $this->faker->dateTime()->format('Y-m-d H:i:s'),
            'end' => $this->faker->dateTime()->format('Y-m-d H:i:s'),
            'attendants' => 'Camat',
            'description' => '<p>'.$this->faker->name().'</p>',
            'status' => 'OPEN',
        ];
    }
}
