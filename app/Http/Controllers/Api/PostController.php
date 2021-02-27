<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Orion\Concerns\DisableAuthorization;
use Orion\Http\Controllers\Controller;
use Orion\Http\Requests\Request;
use Illuminate\Database\Eloquent\Builder;

class PostController extends Controller
{
    use DisableAuthorization;

    protected $model = Post::class;

    protected function limit(): int
    {
        return 48;
    }

    protected function includes() : array
    {
        return ['poster'];
    }

    protected function alwaysIncludes() : array
    {
        return [
            'poster',
        ];
    }

    protected function keyName(): string
    {
        return 'slug';
    }

    protected function filterableBy() : array
    {
        return ['category'];
    }

    protected function buildFetchQuery(Request $request, array $requestedRelations): Builder
    {
        $query = parent::buildFetchQuery($request, $requestedRelations);

        $query->withRating();

        $query->orderBy('rating', 'desc');

        $query->orderBy('created_at', 'desc');

        return $query;
    }

    protected function afterIndex(Request $request, $entities)
    {
        // hide the content in index
        $entities->makeHidden('content');
    }
}
