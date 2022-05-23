<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class NewsController extends Controller
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $municipalities = QueryBuilder::for(News::class)
            ->allowedFilters(['name'])
            ->defaultSort('-created_at')
            ->paginate()
            ->appends(request()->query());

        return NewsResource::collection($municipalities);
    }
    /**
     * @param Request $request
     * @return NewsResource
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255'
        ]);

        $notification = News::create($request->input());

        return new NewsResource($notification);
    }

    /**
     * @param Request $request
     * @param News $news
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function update(Request $request, News $news)
    {
        $request->validate([
            'name'      => 'sometimes|required|string|max:255'
        ]);

        $news->update($request->input());

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param News $news
     * @return NewsResource
     */
    public function show(News $news)
    {
        return new NewsResource($news);
    }

    /**
     * @param News $news
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function destroy(News $news)
    {
        $news->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
