<?php

namespace App\Jobs\Post;

use App\Exceptions\NoValidImageFound;
use App\Models\Post;
use App\Services\ImageUrlService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Log\LogManager;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class PostLinkImageService implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $post;
    private $link;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Post $post, string $link)
    {
        $this->post = $post;
        $this->link = $link; 
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ImageUrlService $image_url_service, LogManager $log)
    {
        try {
            $image_url = $image_url_service->getImageUrl($this->link);
    
            $file_name = Str::random(60);
    
            $this->post->addMediaFromUrl($image_url)
                ->preservingOriginal(true)
                ->usingName($file_name)
                ->usingFileName($file_name)
                ->toMediaCollection('image');
                
        } catch (NoValidImageFound $e) {
            $log->info($e->getMessage());
        }
    }
}
