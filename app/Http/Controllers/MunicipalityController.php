<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMunicipalityRequest;
use App\Http\Requests\UpdateMunicipalityRequest;
use App\Http\Resources\MunicipalityResource;
use App\Models\Municipality;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class MunicipalityController extends Controller
{

    public function getAll()
    {
        return MunicipalityResource::collection(Municipality::where('disabled', 0)->get());
    }

    public function index()
    {
        $municipalities = QueryBuilder::for(Municipality::class)
            ->where('disabled', 0)
            ->allowedFilters(['name'])
            ->defaultSort('-created_at')
            ->paginate()
            ->appends(request()->query());

        return MunicipalityResource::collection($municipalities);
    }

    /**
     * @param Municipality $municipality
     * @return MunicipalityResource
     */
    public function show(Municipality $municipality)
    {
        $data = $municipality->load('district');
        return new MunicipalityResource($data);
    }

    /**
     * @param CreateMunicipalityRequest $request
     * @return MunicipalityResource
     */
    public function store(CreateMunicipalityRequest $request)
    {
        $municipality = Municipality::create(['name' => $request->name]);
        return new MunicipalityResource($municipality);
    }

    /**
     * @param UpdateMunicipalityRequest $request
     * @param Municipality $municipality
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function update(UpdateMunicipalityRequest $request, Municipality $municipality)
    {
        $municipality->update($request->input());
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
