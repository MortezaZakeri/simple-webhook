<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract {
    use Authenticatable, Authorizable;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'name',
        'email',
        'webhook_limit',
        'permission_id',
    ];

    protected $hidden = [
        'password',
    ];

    // each user must belongs to single permission
    public function permission() {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    // user can have many webhooks , limited by flag in user table
    public function webhooks() {
        return $this->hasMany(Webhook::class, 'user_id');
    }
}
