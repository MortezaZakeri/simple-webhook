<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Laravel\Lumen\Auth\Authorizable;

class User extends CustomModel implements AuthenticatableContract, AuthorizableContract {
    use Authenticatable, Authorizable;


    protected $fillable = [
        'name',
        'email',
        'password',
        'webhook_limit',
        'permission_id',
    ];

    protected $hidden = [
        //'password',
    ];

    // each user must belongs to single permission
    public function permission() {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    // user can have many webhooks , limited by flag in user table
    public function webhooks() {
        return $this->hasMany(Webhook::class, 'user_id');
    }

    /**
     * user allowed to add a new webhook , not exceed
     * @return bool
     */
    public function canHaveWebhook(): bool {
        if (!isset($this)) {
            return false;
        }
        return $this->webhook_limit > $this->webhooks()->count();
    }

    /**
     * add extra webhook ticket to create some
     * @param int
     * @return void
     */
    public function addToWebhookLimit(int $count): void {
        $this->increment('webhook_limit', $count);
    }
}
