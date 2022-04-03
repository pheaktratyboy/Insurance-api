<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriberPolicy extends Model
{
    use HasFactory;

    protected $fillable = [
        'policy_id',
        'subscriber_id',
        'payment_method',
    ];

    protected $casts = [
        'policy_id'      => 'integer',
        'subscriber_id'  => 'integer',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @param $request
     * @return $this
     */
    public function updatePolicy($request)
    {
        $this->fill($request);
        $this->save();

        $this->subscriber->cacheCalculationTotalPrice();

        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function policy()
    {
        return $this->belongsTo(Policy::class);
    }
}
