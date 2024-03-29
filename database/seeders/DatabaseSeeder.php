<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersSeeder::class);

        if (config('app.env') === 'local') {
            $this->call(PostsSeeder::class);
            $this->call(CommentsSeeder::class);
            $this->call(VotesSeeder::class);
        }
    }
}
