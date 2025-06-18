<?php

namespace App\Models;

use App\Models\BaseModel;

class Rack extends BaseModel
{
    protected $fillable = [
        'sort_order',
        'rack_number',
        'location',
        'description',
        'capacity',

        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function book()
    {
        return $this->hasMany(Book::class);
    }

}
