<?php

namespace App\Models;

use App\Enums\StatusType;
use App\Traits\Blamable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory, Blamable;

    protected $fillable = [
        'user_id',
        'subscriber_id',
        'note',
        'status',
        'attachments',
    ];

    protected $casts = [
        'user_id'           => 'integer',
        'subscriber_id'     => 'integer',
        'attachments'       => 'array',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscriber(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Subscriber::class);
    }

    public function allowOnlyStatusPending(): Claim
    {
        if ($this->status == StatusType::Pending) {
            abort('422', 'Sorry, this claim allow only status pending.');
        }
        return $this;
    }

    public function notAllowIfStatusApproved(): Claim
    {
        if ($this->status == StatusType::Approved) {
            abort('422', 'Sorry, this claim has been approved.');
        }
        return $this;
    }
}
