<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\Rating;
use Illuminate\Console\Command;

class PostRatingDecayer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rating:decay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Post::query()->cursor()->each(function($post) {
            $post->calculateRatings();
        });
    }
}
