<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class NotificationController extends Controller
{

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $municipalities = QueryBuilder::for(Notification::class)
            ->allowedFilters(['name'])
            ->defaultSort('-created_at')
            ->paginate()
            ->appends(request()->query());

        return NotificationResource::collection($municipalities);
    }
    /**
     * @param Request $request
     * @return NotificationResource
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'message'   => 'required|string|max:255'
        ]);

        $notification = Notification::create($request->input());

        return new NotificationResource($notification);
    }

    /**
     * @param Request $request
     * @param Notification $notification
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function update(Request $request, Notification $notification)
    {
        $request->validate([
            'name'      => 'sometimes|required|string|max:255',
            'message'   => 'sometimes|required|string|max:255',
            'is_read'   => 'sometimes|required|boolean'
        ]);

        $notification->update($request->input());

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Notification $notification
     * @return NotificationResource
     */
    public function show(Notification $notification)
    {
        return new NotificationResource($notification);
    }

    /**
     * @param Notification $notification
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
