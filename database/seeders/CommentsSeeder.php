<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CommentsSeeder extends Seeder
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

        $faker = Factory::create();

        Comment::factory()
            ->count(100)
            ->make()
            ->each(function (Comment $comment) use ($users, $posts, $faker) {
                $user = $users->random();

                $post = $posts->random();

                $comment->posted_by = $user->id;
                $comment->post_id = $post->id;

                // malas ku buat nested comment, remove this code if I want
                $comment->save();
                return true;

                // remove code above
                if (!$post->hasComments()) {
                    $comment->save();
                    return true;
                }

                // randomly determine if $_comment has child comment or not
                // if ($faker->boolean(60)) {
                //     return true;
                // }

                $_comment = $post->comments()->inRandomOrder()->first();

                $comment->parent_id = $_comment->id;
                $comment->save();

                return true;
            });
    }
}
