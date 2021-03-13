<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Orion\Concerns\DisableAuthorization;
// use Orion\Http\Controllers\Controller;
use Orion\Http\Requests\Request;
use Illuminate\Database\Eloquent\Builder;

class PostController extends Controller
{
    use DisableAuthorization;

    private $per_page_limit = 8;

    public function index(Request $request)
    {
        $posts = Post::with('poster')
            ->withRating()
            ->orderBy('rating', 'desc')
            ->orderBy('created_at', 'desc');

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

    public function store(Request $request)
    {

    }
}
