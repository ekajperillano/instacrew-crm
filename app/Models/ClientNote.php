<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Carbon\Carbon;

class ClientNote extends Model
{
    use HasFactory, Uuids;

    protected $guarded = [
        'id',
    ];

    protected $appends = [
        'au_created_at'
    ];

    public function client() {
        return $this->belongsTo('App\Models\Client');
    }

    public function user() {
    	return $this->belongsTo('App\Models\User');
    }

    public function getAuCreatedAtAttribute() {
        return Carbon::parse($this->created_at)->format(config('constants.au_date_time_format'));
    }
}
