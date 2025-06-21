<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Crypt;

class Query extends BaseModel
{
    protected $fillable = [
        'name',
        'email',
        'contact',
        'address',
        'message',
        'ip_address',
        'user_agent',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [
            'decrypted_email',
            'decrypted_contact',
            'decrypted_address',
            'decrypted_message',
        ]);
    }

    // Accessor to decrypt email when accessed
    public function getDecryptedEmailAttribute()
    {
        return $this->email ? Crypt::decryptString($this->email) : null;
    }

    // Accessor to decrypt contact when accessed
    public function getDecryptedContactAttribute()
    {
        return $this->contact ? Crypt::decryptString($this->contact) : null;
    }

    // Accessor to decrypt address when accessed
    public function getDecryptedAddressAttribute()
    {
        return $this->address ? Crypt::decryptString($this->address) : null;
    }

    // Accessor to decrypt message when accessed
    public function getDecryptedMessageAttribute()
    {
        return $this->message ? Crypt::decryptString($this->message) : null;
    }
}
