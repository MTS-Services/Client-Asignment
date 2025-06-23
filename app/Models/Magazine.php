<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;

class Magazine extends BaseModel
{
    protected $fillable = [
        'title',
        'sort_order',
        'slug',
        'description',
        'cover_image',
        'status',
        'url',

        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [

            'status_label',
            'status_color',
            'status_btn_label',
            'status_btn_color',

            'modified_image',
        ]);
    }

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;

    public static function statusList(): array
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
        ];
    }
    public function getStatusLabelAttribute()
    {
        return self::statusList()[$this->status];
    }

    public function getStatusColorAttribute()
    {
        return $this->status == self::STATUS_ACTIVE ? 'badge-success' : 'badge-error';
    }

    public function getStatusBtnLabelAttribute()
    {
        return $this->status == self::STATUS_ACTIVE ? self::statusList()[self::STATUS_INACTIVE] : self::statusList()[self::STATUS_ACTIVE];
    }

    public function getStatusBtnColorAttribute()
    {
        return $this->status == self::STATUS_ACTIVE ? 'btn-error' : 'btn-success';
    }
    public function getModifiedImageAttribute()
    {
        return storage_url($this->cover_image);
    }
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }
}
