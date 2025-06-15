<?php

namespace App\Models;

use App\Models\AuthBaseModel;

class Category extends AuthBaseModel
{
    protected $fillable = [
        'name',
        'slug',
        'description',

        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function book()
    {
        return $this->hasMany(Book::class);
    }
}
