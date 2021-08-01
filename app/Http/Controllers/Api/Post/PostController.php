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
use App\Services\ImageUrlService;
use App\Services\PostService;
use App\Services\PostTypeService;
use App\Services\YoutubeService;
use Error;
use Illuminate\Support\Arr;
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

            if ($post_data['post_type'] === 'link') {
                $yts = app(YoutubeService::class);
                $post_data['link'] = $yts->formatUrlIfYoutubeLink($post_data['link']);
                $post_data['post_type'] = $yts->isYoutubeLink($post_data['link']) ? 'video' : $post_data['post_type'];
            }

            /** @var Post $post */
            $post = $user->posts()->create($post_data);

            $file_name = Str::random(60);

            if ($request->input('post_type') === 'link') {
                $link = $request->input('link');
                $post_id = $post->id;

                dispatch(function () use ($link, $post_id) {
                    $image_url_service = app(ImageUrlService::class);
                    $image_url = $image_url_service->getImageUrl($link, $post_id);

                    $file_name = Str::random(60);
                    $post = Post::find($post_id);

                    $post->addMediaFromUrl($image_url)
                        ->usingName($file_name)
                        ->usingFileName($file_name)
                        ->toMediaCollection('image');
                });
            } else if ($request->hasFile('image')) {
                // most likely post type is image
                $post->addMediaFromRequest('image')
                    ->usingName($file_name)
                    ->usingFileName($file_name)
                    ->toMediaCollection('image');
            } else {
                // most likely post type is discussion
                $file_name = 'image/' . Arr::random([1, 2, 3]) . '.jpeg';
                $post->addMedia(public_path($file_name))
                    ->preservingOriginal(true)
                    ->usingName($file_name)
                    ->usingFileName($file_name)
                    ->toMediaCollection('image');
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
