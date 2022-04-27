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
        'disabled',
    ];

    protected $casts = [
        'staff_count'   => 'integer',
        'logo'          => 'array',
        'disabled'      => 'boolean',
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
    public function setUsersUnderCompany($items)
    {
        $newItems = collect($items)->map(function ($item)  {

            $user = CompanyUser::where('user_id', $item)->first();
            if ($user) {
                abort('422', 'the user exists with name (' . $user->user_id . '), please try with other user id.');
            }

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

    public function notAllowIfItemAlreadyUsed()
    {
        $exist = Subscriber::firstWhere('company_id', $this->id);
        if ($exist) {
            abort('422', 'Sorry, we can not allow doing the action because this item has already been used.');
        }
        return $this;
    }
}
