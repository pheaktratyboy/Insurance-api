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

    public function index()
    {
        $municipalities = QueryBuilder::for(Municipality::class)
            ->allowedFilters(['name'])
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
        return new MunicipalityResource($municipality);
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
