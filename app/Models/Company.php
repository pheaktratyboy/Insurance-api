<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'staff_count',
        'logo',
    ];

    protected $casts = [
        'staff_count'   => 'integer',
        'logo'          => 'array',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function company_employees()
    {
        return $this->hasMany(CompanyUsers::class);
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function createNewCompany(Request $request)
    {
        $this->fill($request->input());
        $this->save();

        return $this;
    }

    /**
     * @param $items
     */
    public function addUserUnderCompany($items)
    {
        $newItems = collect($items)->map(function ($item)  {
            return new CompanyUsers($item);
        });

        $this->company_employees()->saveMany($newItems);
    }
}
