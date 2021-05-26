<?php

namespace App\Models;
use Jenssegers\Mongodb\Eloquent\Model;

class SocialIdentity extends Model
{
    protected $fillable = ['user_id', 'provider_name', 'provider_id'];

    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
}