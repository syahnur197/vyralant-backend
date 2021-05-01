<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $images = [
            "/fake_users/users/1.jpg",
            "/fake_users/users/2.jpg",
            "/fake_users/users/3.jpg",
            "/fake_users/users/4.jpg",
            "/fake_users/users/5.jpg",
            "/fake_users/users/6.jpg",
            "/fake_users/users/7.jpg",
            "/fake_users/users/8.jpg",
        ];

        return [
            'name' => $this->faker->name,
            'username' => $this->faker->unique()->userName,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => 'password',
            'remember_token' => Str::random(10),
            'image_url' => collect($images)->random(), // temporary
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    public function testUser()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'admin',
                'username' => 'admin',
                'email' => 'admin@admin.com',
            ];
        });
    }
}
