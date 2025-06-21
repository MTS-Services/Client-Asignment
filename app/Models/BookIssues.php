<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;

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
        'fine_status',
        'notes',

        'creater_id',
        'updater_id',
        'deleter_id',

        'creater_type',
        'updater_type',
        'deleter_type',
    ];

    protected static function booted()
    {
        static::updated(function ($bookIssue) {
            if ($bookIssue->isDirty('status')) {
                if ($bookIssue->status === self::STATUS_ISSUED) {
                    $bookIssue->book?->decrement('available_copies');
                } elseif ($bookIssue->status === self::STATUS_RETURNED) {
                    $bookIssue->book?->increment('available_copies');
                } elseif ($bookIssue->status === self::STATUS_PENDING && $bookIssue->issued_by !== null) {
                    $bookIssue->book?->increment('available_copies');
                }
            }
        });
        static::created(function ($bookIssue) {
            // No need for isDirty here, just check the status
            if ($bookIssue->status === self::STATUS_ISSUED) {
                $bookIssue->book?->decrement('available_copies');
            }
        });
    }


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [

            'status_label',
            'status_color',
            'fine_status_label',
            'fine_status_color',
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
            self::STATUS_LOST => 'badge-error',
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

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }
    public function scopeIssued(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ISSUED);
    }
    public function scopeReturned(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_RETURNED);
    }
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_OVERDUE);
    }
    public function scopeLost(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_LOST);
    }


    public const FINE_PAID = 1;
    public const FINE_UNPAID = 2;
    public static function fineStatusList(): array
    {
        return [
            self::FINE_PAID => 'Paid',
            self::FINE_UNPAID => 'Unpaid',
        ];
    }

    public function getFineStatusLabelAttribute()
    {
        return self::fineStatusList()[$this->fine_status] ?? 'Not Applicable';
    }
    public function getFineStatusColorAttribute()
    {
        return match ($this->fine_status) {
            self::FINE_PAID => 'badge-success',
            self::FINE_UNPAID => 'badge-error',
            default => 'badge-secondary',
        };
    }
    public function scopeSelf(Builder $query): Builder
    {
        return $query->where('user_id', user()->id);
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('fine_status', self::FINE_PAID);
    }
    public function scopeUnpaid(Builder $query): Builder
    {
        return $query->where('fine_status', self::FINE_UNPAID);
    }
}
