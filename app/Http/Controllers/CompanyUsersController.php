<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Models\CompanyUsers;
use Spatie\QueryBuilder\QueryBuilder;

class CompanyUsersController extends Controller
{
    public function index()
    {
        $company = QueryBuilder::for(CompanyUsers::class)
            ->allowedFilters(['name'])
            ->defaultSort('-created_at')
            ->paginate()
            ->appends(request()->query());

        return CompanyResource::collection($company);
    }

}
