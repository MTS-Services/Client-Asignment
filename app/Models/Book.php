<?php

namespace App\Models;

use App\Models\BaseModel;

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
    ];


      public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [

            'status_label',
            'status_color',
            // 'status_btn_label',
            // 'status_btn_color',
        ]);
    }

    public const STATUS_AVAILABLE = 1;
    public const STATUS_MAINTENANCE = 2;
    public const STATUS_RETIRED = 3;

    public static function statusList(): array
    {
        return [
            self::STATUS_AVAILABLE => 'Available',
            self::STATUS_MAINTENANCE => 'Maintenance',
            self::STATUS_RETIRED => 'Retired',
        ];
    }
    public function getStatusLabelAttribute()
    {
        return self::statusList()[$this->status];
    }

   public function getStatusColorAttribute()
{
    return match ($this->status) {
        self::STATUS_AVAILABLE => 'badge-success',
        self::STATUS_MAINTENANCE => 'badge-warning',
        self::STATUS_RETIRED => 'badge-danger',
        default => 'badge-secondary',
    };
}

    public function getModifiedImageAttribute()
    {
        return storage_url($this->cover_image);
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
}
