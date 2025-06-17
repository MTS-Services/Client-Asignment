<?php

namespace App\Models;

use App\Models\AuthBaseModel;
use Spatie\Permission\Traits\HasRoles;

class Admin extends AuthBaseModel
{
    use HasRoles;
    protected $guard = 'admin';
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role_id',
        'status',
        'email_verified_at',
        'email_otp',
        'email_otp_expires_at',
        'last_otp_sent_at',
        'image',

        'created_by',
        'updated_by',
        'deleted_by',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
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

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id')->select(['name', 'id']);
    }
    public function issuedBy()
    {
        return $this->hasMany(BookIssues::class, 'issued_by');
    }
}
