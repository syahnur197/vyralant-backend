<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use App\Services\CategoryService;
use App\Services\PostTypeService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::inRandomOrder()->first();

        return [
            'category' => CategoryService::getRandomCategory(),
            'post_type' => Arr::random(['image', 'discussion']),
            'title' => $this->faker->sentence(),
            'content' => $this->faker->sentences($this->faker->numberBetween(5, 15), true),
            'posted_by' => $user->id,
        ];
    }
}
