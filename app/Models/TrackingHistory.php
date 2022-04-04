<?php

namespace App\Models;

use App\Enums\TrackingType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_kh',
        'data',
        'model',
        'type',
        'subscriber_id',
        'user_id',
    ];

    protected $casts = [
        'data'          => 'array',
        'subscriber_id' => 'integer',
        'user_id'       => 'integer',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @param $data
     * @param TrackingType $type
     */
    public function createSubscriberTracking($data, $type) {

        $user = auth()->user();
        $tracking = new TrackingHistory;
        $tracking->type             = $type;
        $tracking->data             = $data;
        $tracking->reference_id     = $data->id;
        $tracking->user_id          = $user->id;
        $tracking->model            = Subscriber::class;
        $tracking->save();
    }
}
