<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;

class Book extends BaseModel
{

    protected $fillable = [
        'sort_order',
        'title',
        'slug',
        'isbn',
        'description',
        'category_id',
        'publisher_id',
        'rack_id',
        'publication_date',
        'pages',
        'language',
        'price',
        'cover_image',
        'total_copies',
        'available_copies',
        'status', // 1: Available, 2: Maintenance, 3: Retired
        'file',

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
        ]);
    }


    public const STATUS_AVAILABLE = 1;
    public const STATUS_UNAVAILABLE = 0;

    public static function statusList(): array
    {
        return [
            self::STATUS_AVAILABLE => 'Available',
            self::STATUS_UNAVAILABLE => 'Unavailable',
        ];
    }
    public function getStatusLabelAttribute()
    {
        return self::statusList()[$this->status];
    }

    public function getStatusColorAttribute()
    {
        return $this->status == self::STATUS_AVAILABLE ? 'badge-success' : 'badge-error';
    }

    public function getStatusBtnLabelAttribute()
    {
        return $this->status == self::STATUS_AVAILABLE ? self::statusList()[self::STATUS_UNAVAILABLE] : self::statusList()[self::STATUS_AVAILABLE];
    }

    public function getStatusBtnColorAttribute()
    {
        return $this->status == self::STATUS_AVAILABLE ? 'btn-error' : 'btn-success';
    }

    public function getModifiedImageAttribute()
    {
        return storage_url($this->cover_image);
    }
    public function getModifiedFileAttribute()
    {
        return $this->file ? asset('storage/' . $this->file) : Null;
    }



    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function publisher()
    {
        return $this->belongsTo(Publisher::class, 'publisher_id');
    }
    public function rack()
    {
        return $this->belongsTo(Rack::class, 'rack_id');
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_AVAILABLE)->where('available_copies', '>', 0);
    }
    public function scopeUnavailable(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_UNAVAILABLE);
    }


}
