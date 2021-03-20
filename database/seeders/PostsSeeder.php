<?php

namespace Database\Seeders;

use App\Models\Post;
use Faker\Generator;
use Illuminate\Database\Seeder;

class PostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = app(Generator::class);
        Post::factory()
            ->count(50)
            ->create()
            ->each(function ($post, $key) use ($faker) {
                $number = $faker->numberBetween(1, 8);
                $post->media()->create([
                    'collection_name' => 'image',
                    'name' => 'fake_posts/' . $number . '.jpg',
                    'file_name' => 'fake_posts/' . $number . '.jpg',
                    'mime_type' => 'image/jpeg',
                    'disk' => 's3',
                    'conversions_disk' => 's3',
                    'size' => 0,
                    'manipulations' => [],
                    'custom_properties' => [],
                    'generated_conversions' => [],
                    'responsive_images' => [],
                    'order_column' => $key,
                ]);
            });
    }
}
