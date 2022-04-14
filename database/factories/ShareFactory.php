<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Share>
 */
class ShareFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
//            'id' => $this->faker->regexify('[A-Za-z0-9]{5}'), #This is a thing if needed
            'sender_id' => $this->faker->uuid(),    #Should be linked to an user
            'recipient_id' => $this->faker->uuid(), #Same
            'resource_id' => $this->faker->regexify('[A-Za-z0-9]{5}') #I guess this to need to be linked to a REAL location
        ];
    }
}
