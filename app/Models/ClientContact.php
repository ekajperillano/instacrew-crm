<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class ClientContact extends Model
{
    use HasFactory, Uuids;

    protected $guarded = [
        'id',
    ];

    public function client() {
        return $this->belongsTo('App\Models\Client');
    }
}