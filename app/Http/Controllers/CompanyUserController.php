<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCompanyUsersRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\CompanyUserResource;
use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class CompanyUserController extends Controller
{
    public function index()
    {
        $company = QueryBuilder::for(CompanyUser::class)
            ->allowedFilters(['name'])
            ->defaultSort('-created_at')
            ->paginate()
            ->appends(request()->query());

        return CompanyResource::collection($company);
    }

    /**
     * @param CreateCompanyUsersRequest $request
     * @param Company $company
     * @return CompanyResource
     */
    public function store(CreateCompanyUsersRequest $request, Company $company) {

        $result = DB::transaction(function () use ($request, $company) {

            return $company->addUserUnderCompany($request->input('users'))->load('employees');
        });

        return new CompanyResource($result);
    }


    public function update() {

    }

    /**
     * @param CompanyUser $companyUsers
     * @return CompanyUserResource
     */
    public function show(CompanyUser $companyUsers) {

        return new CompanyUserResource($companyUsers->load('user'));
    }
}
