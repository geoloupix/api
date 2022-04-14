<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Token>
 */
class TokenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "user_id" => $this->faker->uuid(), #soooo, this shouldn't be used, it NEEDS to be linked to an actual user
            "token" => $this->faker->regexify('[A-Za-z0-9]{128}')
        ];
    }
}
