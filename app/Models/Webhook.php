<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Webhook extends Model {

    protected $fillable = [
        'token',
        'url',
        'verb',  // could has own table (options : GET,POST)
        'user_id',
    ];

    // this webhook belongs to one user
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    // every webhook can be repeat in many log files
    public function logs() {
        return $this->hasMany(WebhookLog::class, 'webhook_id');
    }

}
