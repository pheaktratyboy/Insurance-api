<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MediaController extends Controller
{

    /**
     * @param Request $request
     * @param string $collection
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $collection = 'upload')
    {
        $file  = $request->file('file');
        $media = app('request')->user()->addMedia($file)->toMediaCollection($collection);

        return response()->json([
            'id'  => $media->id,
            'url' => $media->getFullUrl(),
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadMultipleFiles(Request $request)
    {
        $medias = [];
        foreach ($request->file('files') as $file) {
            $media = app('request')->user()->addMedia($file)->toMediaCollection('upload');
            array_push($medias, $media);
        }
        $transformMedias = collect($medias)->transform(function ($media) {
            return [
                'id'  => $media->id,
                'url' => $media->getFullUrl(),
            ];
        });

        return response()->json($transformMedias);
    }

    /**
     * @param Request $request
     * @return int
     */
    public function destroy(Request $request)
    {
        $payload = json_decode($request->getContent(), true);
        optional(Media::find(collect($payload)->get('id')))->delete();

        return Response::HTTP_NO_CONTENT;
    }
}
