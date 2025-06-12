<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $appends = [
        'modified_image',

        'created_at_formatted',
        'updated_at_formatted',
        'deleted_at_formatted',

        'created_at_human',
        'updated_at_human',
        'deleted_at_human',
    ];



    public function getModifiedImageAttribute()
    {
        return auth_storage_url($this->image);
    }

    // Accessor for created time
    public function getCreatedAtFormattedAttribute()
    {
        return timeFormat($this->created_at);
    }

    // Accessor for updated time
    public function getUpdatedAtFormattedAttribute()
    {
        return $this->created_at != $this->updated_at ? timeFormat($this->updated_at) : 'N/A';
    }

    // Accessor for deleted time
    public function getDeletedAtFormattedAttribute()
    {
        return $this->deleted_at ? timeFormat($this->deleted_at) : 'N/A';
    }

    // Accessor for created time human readable
    public function getCreatedAtHumanAttribute()
    {
        return timeFormatHuman($this->created_at);
    }

    // Accessor for updated time human readable
    public function getUpdatedAtHumanAttribute()
    {
        return $this->created_at != $this->updated_at ? timeFormatHuman($this->updated_at) : 'N/A';
    }

    // Accessor for deleted time human readable
    public function getDeletedAtHumanAttribute()
    {
        return $this->deleted_at ? timeFormatHuman($this->deleted_at) : 'N/A';
    }
}
