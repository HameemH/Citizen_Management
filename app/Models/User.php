<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'verification_status',
        'password',
        'nid_number',
        'full_name',
        'date_of_birth',
        'father_name',
        'mother_name',
        'permanent_address',
        'present_address',
        'phone_number',
        'nid_front_image',
        'nid_back_image',
        'passport_photo',
        'verification_requested_at',
        'verified_at',
        'verified_by',
        'rejected_at',
        'rejected_by',
        'rejection_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is citizen
     */
    public function isCitizen()
    {
        return $this->role === 'citizen';
    }

    /**
     * Check if user is verified
     */
    public function isVerified()
    {
        return $this->verification_status === 'verified';
    }

    /**
     * Check if user's NID is blocked/fake
     */
    public function isNidBlocked()
    {
        if (empty($this->national_id)) {
            return false;
        }
        
        return \App\Models\FakeNid::isBlocked($this->national_id);
    }

    /**
     * Get the reason why user's NID is blocked
     */
    public function getNidBlockedReason()
    {
        if (empty($this->national_id)) {
            return null;
        }
        
        return \App\Models\FakeNid::getBlockedReason($this->national_id);
    }

    /**
     * Check if user's NID is available for verification
     */
    public function isNidAvailableForVerification()
    {
        if (empty($this->national_id)) {
            return false;
        }
        
        return \App\Models\FakeNid::isAvailableForVerification($this->national_id);
    }

    /**
     * Get NID verification status
     */
    public function getNidVerificationStatus()
    {
        if (empty($this->national_id)) {
            return ['status' => 'no_nid', 'message' => 'No NID provided'];
        }
        
        return \App\Models\FakeNid::getVerificationStatus($this->national_id);
    }

    /**
     * Mark user's NID as verified
     */
    public function markNidAsVerified($verifiedByUserId)
    {
        if (empty($this->national_id)) {
            return false;
        }
        
        return \App\Models\FakeNid::markAsVerified($this->national_id, $verifiedByUserId);
    }
}
