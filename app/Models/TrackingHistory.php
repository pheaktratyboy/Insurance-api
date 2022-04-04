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
    ];

    protected $casts = [
        'data' => 'array',
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
        $tracking->type = $type;
        $tracking->data = $data;
        $tracking->model = Subscriber::class;
        $tracking->save();
    }
}
