<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePost;
use App\Models\Post;
use Exception;
use Orion\Concerns\DisableAuthorization;
// use Orion\Http\Controllers\Controller;
use Orion\Http\Requests\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    use DisableAuthorization;

    private $per_page_limit = 8;

    public function index(Request $request)
    {
        $posts = Post::with('poster')
            ->withRating()
            ->orderBy('created_at', 'desc')
            ->orderBy('rating', 'desc');

        $posts = $posts->paginate($this->per_page_limit);

        return $posts;
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

        return $posts;
    }

    public function show($slug)
    {
        $post = Post::with('poster')
            ->withRating()
            ->orderBy('rating', 'desc')
            ->orderBy('created_at', 'desc')
            ->where('slug', $slug)
            ->first();

        return [
            'data' => $post,
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


        try {
            $post = $user->posts()->create($post_data);

            return response()->json([
                'success' => 'false',
                'message' => 'Successfully created a post!',
                'post'    => $post,
            ], 201);

        } catch (Exception $e) {
            Log::error("PostController@store " . json_encode($e));

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit a post! Please try again!',
            ], 400);
        }

    }
}
