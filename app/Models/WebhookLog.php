<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class WebhookLog extends Model {

    protected $fillable = [
        'webhook_id',
        'status_code',
        'message',
        'user_id',
        'status'
    ];


    // every logs belongs to one webhook
    public function webhook() {
        return $this->belongsTo(Webhook::class, 'webhook_id');
    }


}
