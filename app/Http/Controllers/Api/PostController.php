<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePost;
use App\Http\Resources\Posts\PostResource;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileUnacceptableForCollection;

class PostController extends Controller
{
    private $per_page_limit = 8;

    public function index(Request $request)
    {
        $posts = Post::with('poster', 'media')
            ->withRating()
            ->orderBy('created_at', 'desc')
            ->orderBy('rating', 'desc');

        $posts = $posts->paginate($this->per_page_limit);

        return PostResource::collection($posts);
    }

    public function search(Request $request)
    {

        $category = $request->input('category');

        $posts = Post::with('poster')
            ->withRating()
            ->orderBy('rating', 'desc')
            ->orderBy('created_at', 'desc')
            ->where('category', strtolower($category));

        $posts = $posts->paginate($this->per_page_limit);

        return PostResource::collection($posts);
    }

    public function show($slug)
    {
        $post = Post::with('poster', 'media')
            ->withRating()
            ->orderBy('rating', 'desc')
            ->orderBy('created_at', 'desc')
            ->where('slug', $slug)
            ->first();

        return [
            'data' => new PostResource($post),
        ];
    }

    public function store(StorePost $request)
    {
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
