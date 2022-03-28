<?php

namespace App\Http\Controllers;

use App\Exceptions\MunicipalityException;
use App\Http\Resources\MunicipalityResource;
use App\Models\Municipality;
use Illuminate\Http\Request;
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
     */
    public function show(Municipality $municipality)
    {
        return new MunicipalityResource($municipality);
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $oldMunicipality = Municipality::firstWhere('name', $request->name);
        if ($oldMunicipality) {
            throw MunicipalityException::exist();
        }

        $municipality = Municipality::create(['name' => $request->name]);

        return new MunicipalityResource($municipality);
    }

    /**
     * @param Request $request
     * @param Municipality $municipality
     */
    public function update(Request $request, Municipality $municipality)
    {
        $request->validate(['name' => 'string|max:255']);

        $oldMunicipality = Municipality::where('name', $request->name)
            ->where('id', '<>', $municipality->id)
            ->first();

        if ($oldMunicipality) {
            throw MunicipalityException::exist();
        }

        $municipality->update($request->input());

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
