<?php

namespace App\Models;

use App\Enums\BaseRole;
use App\Enums\StatusType;
use App\Traits\Blamable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory, Blamable;

    protected $fillable = [
        'user_id',
        'subject',
        'subscriber_id',
        'note',
        'status',
        'attachments',
        'claimed_at',
        'accident_type',
    ];

    protected $casts = [
        'user_id'           => 'integer',
        'subscriber_id'     => 'integer',
        'attachments'       => 'array',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'claimed_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscriber(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Subscriber::class);
    }

    public function confirmSubscriberHasClaimed()
    {
        $subscriber = $this->subscriber;
        $subscriber->status = StatusType::Claimed;
        $subscriber->claimed_at = Carbon::now();
        $subscriber->update();
    }

    public function notAllowUserSubmitIfStatusStillPending(): Claim
    {
        if ($this->status == StatusType::Pending) {

            abort('422', 'Sorry, you already submitted please wait for the admin to review your request.');
        }

        return $this;
    }

    public function allowOnlyStatusPending(): Claim
    {
        if ($this->status != StatusType::Pending) {
            abort('422', 'Sorry, this claim allow only status pending.');
        }
        return $this;
    }

    public function allowOnlyAdmin()
    {
        $user = auth()->user();
        if (!$user->hasRole([BaseRole::Admin, BaseRole::Master])) {
            abort('422', 'Sorry, You don`t have permission.');
        }
    }

    public function notAllowIfStatusHasApproved(): Claim
    {
        if ($this->status == StatusType::Approved) {
            abort('422', 'Sorry, this claim has been approved.');
        }
        return $this;
    }

    public function notAllowIfStatusRejected(): Claim
    {
        if ($this->status == StatusType::Rejected) {
            abort('422', 'Sorry, we not allow if claim has been rejected.');
        }
        return $this;
    }
}
