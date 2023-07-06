<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriberFactory extends Factory
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
            'app_id'=>$this->faker->randomElement([1,2]),
            'mask_no'=>$this->faker->phoneNumber,
            'subscription_status'=>$this->faker->randomElement(['Subscriber','Pending Charge','Unsubscriber'])
        ];
    }
}
