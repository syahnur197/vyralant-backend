<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePost;
use App\Http\Resources\Posts\PostResource;
use App\Jobs\Post\PostLinkImageService;
use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use App\Services\PostTypeService;
use App\Services\YoutubeService;
use Exception;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\Request;
use Illuminate\Log\LogManager;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileUnacceptableForCollection;
use Spatie\MediaLibrary\MediaCollections\FileAdder;

class PostController extends Controller
{
    private $per_page_limit = 8;

    private $_log;

    private $_yts;

    private $_db;

    public function __construct(
        LogManager $log,
        YoutubeService $yts,
        DatabaseManager $db,
    ) {
        $this->_log = $log;
        $this->_yts = $yts;
        $this->_db = $db;
    }

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

        $this->_log->info('Searching post for category: ' . $category);

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

        $this->_log->info('Store post: ' . json_encode($post_data));

        $this->_db->beginTransaction();
        try {
            /** @var Post $post */
            $post = $user->posts()->create(
                $this->_getYoutubeLinkIfPostTypeLink($post_data)
            );

            $file_name = Str::random(60);

            $this->_getFileAdder($request, $post)
                ->usingName($file_name)
                ->preservingOriginal(true)
                ->usingFileName($file_name)
                ->toMediaCollection('image');

            if ($request->input('post_type') === PostTypeService::LINK) {
                PostLinkImageService::dispatch(
                    $post,
                    $request->input('link'),
                )->delay(now()->addSeconds(5));
            }

            $user->upvote($post);
        } catch (FileUnacceptableForCollection $e) {
            $this->_log->error("PostController@store " . json_encode($e->getMessage()));
            $this->_db->rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (Exception $e) {
            $this->_log->error("PostController@store " . json_encode($e->getMessage()));
            $this->_log->error($e->getTraceAsString());
            $this->_db->rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit a post! Please try again!',
            ], 400);
        }

        $this->_db->commit();

        return response()->json([
            'success' => true,
            'message' => 'Successfully created a post!',
            'post' => $post,
        ], 201);
    }

    private function _getFileAdder(StorePost $request, Post $post): FileAdder
    {
        if ($request->input('post_type') === PostTypeService::LINK) {
            $image_url = public_path('image/4.jpeg');
            return $post->addMediaFromUrl($image_url);
        } else if ($request->hasFile('image')) {
            return $post->addMediaFromRequest('image');
        } else {
            $file_name = 'image/' . Arr::random([1, 2, 3]) . '.jpeg';
            return $post->addMedia(public_path($file_name));
        }

        throw new Exception('Invalid post type');
    }

    private function _getYoutubeLinkIfPostTypeLink($post_data): array
    {
        if ($post_data['post_type'] !== PostTypeService::LINK) {
            return $post_data;
        }

        $post_data['link'] = $this->_yts->formatUrlIfYoutubeLink($post_data['link']);
        $post_data['post_type'] = $this->_yts->isYoutubeLink($post_data['link']) ? 'video' : $post_data['post_type'];

        return $post_data;
    }
}
