<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class Client extends Model
{
    use HasFactory, SoftDeletes, Uuids;

    protected $guarded = [
        'id',
    ];

    public function socials() {
        return $this->hasMany('App\Models\Social');
    }
}
