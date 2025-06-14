<?php

namespace App\Models;

use App\Models\BaseModel;

class Author extends BaseModel
{
    protected $fillable = [
        'sort_order',
        'name',
        'biography',
        'birth_date',
        'death_date',
        'nationality',
        'image',
    ];
}
