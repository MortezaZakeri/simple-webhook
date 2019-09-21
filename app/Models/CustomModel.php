<?php
/**
 * User: MB
 * Date: 9/21/2019
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CustomModel extends Model {

    public $incrementing = false;

    protected static function boot() {
        parent::boot();
        static::creating(function (Model $model) {
            // id == uuid
            $model->{$model->getKeyName()} = (string)Str::uuid();
        });
    }
}
