<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Permission extends Model {

    protected $fillable = [
        'level',
    ];


    // in each permission might have many users
    public function users() {
        return $this->hasMany(User::class, 'permission_id');
    }
}
