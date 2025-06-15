<?php

namespace App\Models;

use App\Models\AuthBaseModel;

class Category extends AuthBaseModel
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function book()
    {
        return $this->hasMany(Book::class);
    }
}
