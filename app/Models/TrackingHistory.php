<?php

namespace App\Models;

use App\Enums\TrackingType;
use App\Traits\Blamable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingHistory extends Model
{
    use HasFactory, Blamable;

    protected $fillable = [
        'name_kh',
        'data',
        'model',
        'type',
        'subscriber_id',
    ];

    protected $casts = [
        'data'          => 'array',
        'subscriber_id' => 'integer',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    /**
     * @param $data
     * @param TrackingType $type
     */
    public function createSubscriberTracking($data, $type) {

        $tracking = new TrackingHistory;
        $tracking->type             = $type;
        $tracking->data             = $data;
        $tracking->reference_id     = $data->id;
        $tracking->model            = Subscriber::class;
        $tracking->save();
    }
}
