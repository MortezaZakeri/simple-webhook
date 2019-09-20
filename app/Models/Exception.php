<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Exception extends Model {

    protected $fillable = [
        'method',
        'message',
        'code',
        'developer_message',
        'risk',
    ];


}
