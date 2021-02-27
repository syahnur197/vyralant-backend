<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use App\Services\CategoryService;
use App\Services\PostTypeService;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $category_service = app(CategoryService::class);
        $post_type_service = app(PostTypeService::class);

        $user = User::inRandomOrder()->first();

        // temporary
        $images = [
            "/posts/1.jpg",
            "/posts/2.jpg",
            "/posts/3.jpg",
            "/posts/4.jpg",
            "/posts/5.jpg",
            "/posts/6.jpg",
            "/posts/7.jpg",
            "/posts/8.jpg",
        ];

        return [
            'category' => $category_service->getRandomCategory(),
            'post_type' => $post_type_service->getRandomPostType(),
            'title' => $this->faker->sentence(),
            'content' => $this->faker->sentences($this->faker->numberBetween(5,15), true),
            'posted_by' => $user->id,
            'image' => collect($images)->random(),
        ];
    }
}
