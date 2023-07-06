<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AppListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'app_id'=>$this->faker->uuid(),
            'app_password'=>$this->faker->uuid(),
           
            

        ];
    }
}
