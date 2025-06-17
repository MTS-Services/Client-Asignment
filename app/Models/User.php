<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AuthBaseModel;
use Illuminate\Notifications\Notifiable;

class User extends AuthBaseModel
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'sort_order',
        'name',
        'username',
        'email',
        'status',
        'password',
        'email_verified_at',
        'email_otp',
        'email_otp_expires_at',
        'last_otp_sent_at',
        'image',

        'creater_id',
        'creater_type',
        'updater_id',
        'updater_type',
        'deleter_id',
        'deleter_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_otp', // Hide OTP from API responses/serialization
        'email_otp_expires_at', // Hide OTP expiry
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'email_otp_expires_at' => 'datetime',
            'last_otp_sent_at' => 'datetime',
            'email_otp' => 'integer',
        ];
    }

    public function book_issues()
    {
        return $this->hasMany(BookIssues::class);
    }
}
