<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Resources\Posts\PostResource;
use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VoteController
{
    public function upvote($slug, Request $request, PostService $service)
    {
        DB::beginTransaction();
        try {
            $post = Post::whereSlug($slug)
                ->first();

            /** @var User $user */
            $user = $request->user();

            $vote = $user->upvote($post);

            $post = $service->getPostQuery()
                ->whereSlug($slug)
                ->first();

            $post = new PostResource($post);

            DB::commit();

            return response()->json([
                'success' => true,
                'vote' => $vote,
                'post' => $post,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info('VoteController@upvote: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to upvote',
            ], 400);
        }
    }

    public function downvote($slug, Request $request, PostService $service)
    {
        DB::beginTransaction();
        try {
            $post = Post::whereSlug($slug)
                ->first();

            /** @var User $user */
            $user = $request->user();

            $vote = $user->downvote($post);

            $post = $service->getPostQuery()
                ->whereSlug($slug)
                ->first();

            $post = new PostResource($post);

            DB::commit();

            return response()->json([
                'success' => true,
                'vote' => $vote,
                'post' => $post,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info('VoteController@downvote: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to downvote',
            ], 400);
        }
    }
}
