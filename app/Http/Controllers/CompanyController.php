<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Models\Subscriber;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class CompanyController extends Controller
{

    public function index()
    {
        $company = QueryBuilder::for(Company::class)
            ->allowedFilters(['name'])
            ->defaultSort('-created_at')
            ->paginate()
            ->appends(request()->query());

        return CompanyResource::collection($company);
    }

    public function listAllSubscriber()
    {
//        $municipalities = QueryBuilder::for()
//            ->allowedFilters(['name'])
//            ->defaultSort('-created_at')
//            ->paginate()
//            ->appends(request()->query());
//
//        return CompanyResource::collection($municipalities);
    }


    /**
     * @param Company $company
     * @return CompanyResource
     */
    public function show(Company $company)
    {
        $company->load('company_employees.user');

        return new CompanyResource($company);
    }

    /**
     * @param CreateCompanyRequest $request
     * @return CompanyResource
     */
    public function store(CreateCompanyRequest $request)
    {
        $result = DB::transaction(function () use ($request) {

            $company = new Company;
            $company->createNewCompany($request);

            if ($request->has('users')) {
                $company->addUserUnderCompany($request->input('users'));
            }
            return $company;
        });

        return new CompanyResource($result);
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
