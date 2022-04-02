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
    public function employees()
    {
        return $this->hasMany(CompanyUser::class);
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
     * @return $this
     */
    public function addUserUnderCompany($items)
    {
        $newItems = collect($items)->map(function ($item)  {
            return new CompanyUser($item);
        });

        $this->employees()->saveMany($newItems);

        return $this;
    }

    /**
     * @return $this
     */
    public function cacheSumTotalStaff() {
        $this->staff_count = $this->employees()->count();
        $this->save();
        $this->refresh();

        return $this;
    }
}
