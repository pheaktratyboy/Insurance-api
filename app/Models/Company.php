<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Enums\BaseRole;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'staff_count',
        'subscriber_count',
        'logo',
        'disabled',
    ];

    protected $casts = [
        'staff_count'       => 'integer',
        'subscriber_count'  => 'integer',
        'logo'              => 'array',
        'disabled'          => 'boolean',
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

    public function addUserUnderCompany($items) {

        $newItems = collect($items)->map(function ($item)  {

            $user = CompanyUser::where('user_id', $item['user_id'])->first();
            if ($user) {
                abort('422', 'the user exists with id (' . $user->user_id . '), please try with other user id.');
            }

            $item['type'] = BaseRole::Staff;

            return new CompanyUser($item);
        });

        $this->employees()->saveMany($newItems);

        return $this;
    }

    /**
     * @param $items
     * @return $this
     */
    public function setSubscriberUnderCompany($items) {

        $newItems = collect($items)->map(function ($item)  {

            $user = CompanyUser::where('user_id', $item['user_id'])->first();
            if ($user) {
                abort('422', 'the user exists with id (' . $user->user_id . '), please try with other user id.');
            }

            /** update field customer id in subscriber */
            $this->updateCompanyIdWithSubscriber($item['user_id'], $this->id);

            $item['type'] = BaseRole::Subscriber;

            return new CompanyUser($item);
        });

        $this->employees()->saveMany($newItems);

        return $this;
    }

    public function updateCompanyIdWithSubscriber($userId, $companyId) {

        $user = User::find($userId);

        /** update customer id in subscriber */
        if ($user) {
            $profile = $user->profile;
            if ($profile) {
                $profile->update([
                    "company_id" => $companyId
                ]);
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function cacheSumTotalStaff() {
        $this->staff_count = $this->employees()->where('type', BaseRole::Staff)->count();
        $this->save();
        $this->refresh();

        return $this;
    }

    public function cacheSumTotalSubscriber() {
        $this->subscriber_count = $this->employees()->where('type', BaseRole::Subscriber)->count();
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
