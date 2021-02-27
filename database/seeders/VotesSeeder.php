<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class VotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $posts = Post::all();

        $users->each(function ($user) use ($posts) {
            $posts->each(function ($post) use ($user) {
                $faker = Factory::create();
                if ($faker->boolean(80)) {
                    $user->upvote($post);
                } else if ($faker->boolean(40)) {
                    $user->downvote($post);
                }
            });
        });
    }
}
