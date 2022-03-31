<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class CompanyController extends Controller
{

    public function index()
    {
        $municipalities = QueryBuilder::for(Company::class)
            ->allowedFilters(['name'])
            ->defaultSort('-created_at')
            ->paginate()
            ->appends(request()->query());

        return CompanyResource::collection($municipalities);
    }
    /**
     * @param Company $company
     * @return CompanyResource
     */
    public function show(Company $company)
    {
        return new CompanyResource($company);
    }

    /**
     * @param CreateCompanyRequest $request
     * @return CompanyResource
     */
    public function store(CreateCompanyRequest $request)
    {
        $company = Company::create($request->input());
        return new CompanyResource($company);
    }

    /**
     * @param UpdateCompanyRequest $request
     * @param Company $company
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $company->update($request->input());
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
