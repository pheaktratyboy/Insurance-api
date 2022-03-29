<?php

namespace App\Http\Controllers;

use App\Exceptions\DistrictException;
use App\Http\Requests\CreateDistrictRequest;
use App\Http\Requests\UpdateDistrictRequest;
use App\Http\Resources\DistrictResource;
use App\Models\District;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;


class DistrictController extends Controller
{
    public function index() {

        $districts = QueryBuilder::for(District::class)
            ->allowedFilters(['name'])
            ->with('municipality')
            ->paginate()
            ->appends(request()->query());

        return DistrictResource::collection($districts);
    }

    /**
     * @param CreateDistrictRequest $request
     * @return DistrictResource
     */
    public function store(CreateDistrictRequest $request) {
        $district = District::create($request->validated());
        return new DistrictResource($district->load('municipality'));
    }

    /**
     * @param UpdateDistrictRequest $request
     * @param District $district
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     * @throws DistrictException
     */
    public function update(UpdateDistrictRequest $request, District $district) {

        if ($request->has('municipality_id')) {
            $oldDistrict = District::where('name', $district->name)
                ->where('municipality_id', $request->municipality_id)
                ->where('id', '<>', $district->id)
                ->first();

            if ($oldDistrict) {
                throw DistrictException::exist();
            }
        }

        $district->update($request->input());
        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param District $district
     * @return DistrictResource
     */
    public function show(District $district)
    {
        return new DistrictResource($district->load('municipality'));
    }
}
