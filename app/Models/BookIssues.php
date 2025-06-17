<?php

namespace App\Models;

use App\Models\BaseModel;

class BookIssues extends BaseModel
{

    protected $fillable = [
        'sort_order',
        'issue_code',
        'user_id',
        'book_id',
        'issued_by',
        'issue_date',
        'due_date',
        'return_date',
        'returned_by',
        'status',
        'fine_amount',
        'fine_paid',
        'notes',

        'creater_id',
        'updater_id',
        'deleter_id',
        
        'creater_type',
        'updater_type',
        'deleter_type',
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

    public const STATUS_PENDING = 1;
    public const STATUS_ISSUED = 2;
    public const STATUS_RETURNED = 3;
    public const STATUS_OVERDUE = 4;
    public const STATUS_LOST = 5;
    public static function statusList(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_ISSUED => 'Issued',
            self::STATUS_RETURNED => 'Returned',
            self::STATUS_OVERDUE => 'Overdue',
            self::STATUS_LOST => 'Lost',
        ];
    }
    public function getStatusLabelAttribute()
    {
        return self::statusList()[$this->status];
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'badge-error',
            self::STATUS_ISSUED => 'badge-primary',
            self::STATUS_RETURNED => 'badge-success',
            self::STATUS_OVERDUE => 'badge-warning',
            self::STATUS_LOST => 'badge-danger',
            default => 'badge-secondary',
        };
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
    public function issuedBy()
    {
        return $this->belongsTo(Admin::class, 'issued_by');
    }
    public function returnedBy()
    {
        return $this->belongsTo(User::class, 'returned_by');
    }

    
}
