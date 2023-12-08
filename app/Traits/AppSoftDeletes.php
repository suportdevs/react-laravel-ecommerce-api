<?php

namespace App\Traits;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait AppSoftDeletes{
    public function boo(Builder $builder, Model $model) {
        static::deleting(function($model){
            $model->deleted_by = auth()->id();
        });
    }
}