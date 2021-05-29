<?php

namespace App\Http\Controllers\Api\Post;

use Exception;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePost;
use App\Http\Resources\Posts\PostResource;
use App\Models\User;
use App\Services\PostService;
use Error;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileUnacceptableForCollection;
use spekulatius\phpscraper;

class PostController extends Controller
{
    private $per_page_limit = 8;

    public function index(PostService $service)
    {
        $posts = $service->getPostQuery();

        $posts = $posts->paginate($this->per_page_limit);

        return PostResource::collection($posts);
    }

    public function search(Request $request, PostService $service)
    {

        $category = $request->input('category');

        $posts = $service->getPostQuery()
            ->where('category', strtolower($category));

        $posts = $posts->paginate($this->per_page_limit);

        return PostResource::collection($posts);
    }

    public function show(Request $request, $slug)
    {
        $post = Post::with('poster', 'media', 'rating')
            ->withCount('comments')
            ->where('slug', $slug)
            ->first();

        return [
            'data' => new PostResource($post),
        ];
    }

    public function store(StorePost $request)
    {
        /** @var User $user */
        if (!$user = $request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be authenticated to submit a post!',
            ], 400);
        }


        $post_data = $request->validated();

        DB::beginTransaction();
        try {
            /** @var Post $post */
            $post = $user->posts()->create($post_data);

            if ($request->hasFile('image')) {
                $file_name = Str::random(60);
                $post->addMediaFromRequest('image')
                    ->usingName($file_name)
                    ->usingFileName($file_name)
                    ->toMediaCollection('image');
            }

            if ($request->input('post_type') === 'link') {
                $link = $request->input('link');
                $post_id = $post->id;
                dispatch(function () use ($link, $post_id) {
                    $web = new phpscraper();
                    $web->go($link);
                    $images = $web->imagesWithDetails;

                    if (count($images) < 1) {
                        // remove post if there is no picture
                        Post::where('id', $post_id)->delete();
                        throw new Exception('URL has no featured image!');
                    }

                    $current_max_height = 0;
                    $current_image      = [];

                    // grab the image with the highest height
                    // assuming the featured image is the highest
                    foreach ($images as $image) {
                        if ($image['height'] < $current_max_height) continue;

                        $current_max_height = $image['height'];
                        $current_image = $image;
                    }

                    $image_url = $current_image['url'];

                    $file_name = Str::random(60);
                    $post = Post::find($post_id);

                    $post->addMediaFromUrl($image_url)
                        ->usingName($file_name)
                        ->usingFileName($file_name)
                        ->toMediaCollection('image');
                });
            }

            $user->upvote($post);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Successfully created a post!',
                'post'    => $post,
            ], 201);
        } catch (FileUnacceptableForCollection $e) {
            Log::error("PostController@store " . json_encode($e->getMessage()));
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (Exception $e) {
            Log::error("PostController@store " . json_encode($e->getMessage()));
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit a post! Please try again!',
            ], 400);
        }
    }
}
