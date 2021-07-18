<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Requests\Comment\StoreComment;
use App\Http\Resources\Comments\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostCommentController
{
    public function index($slug)
    {
        $post = Post::with(
            'comments.poster',
        )
            ->whereSlug($slug)
            ->first();

        $comments = $post->comments()->latest('created_at')->get();

        $comments = CommentResource::collection($comments);

        return [
            'data' => $comments,
        ];
    }

    public function store(Post $post, StoreComment $request)
    {
        $comment_data = $request->validated();
        $comment = new Comment($comment_data);

        DB::beginTransaction();

        try {
            $success = $post->comments()->save($comment);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'successfully submit a post!',
            ], 201);
        } catch (Exception $error) {
            Log::error("PostCommentController@store" . json_encode($error));
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Fail to submit a post!',
            ], 400);
        }
    }
}
