<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FakeNid extends Model
{
    use HasFactory;

    protected $fillable = [
        "nid",
        "name", 
        "father_name",
        "mother_name",
        "date_of_birth",
        "gender",
        "birth_place",
        "present_address",
        "permanent_address",
        "blood_group",
        "reason",
        "is_blocked",
        "is_verified",
        "verified_at",
        "verified_by"
    ];

    protected $casts = [
        "date_of_birth" => "date",
        "is_blocked" => "boolean",
        "is_verified" => "boolean",
        "verified_at" => "datetime"
    ];

    public static function isBlocked($nid)
    {
        return self::where("nid", $nid)->where("is_blocked", true)->exists();
    }

    public static function getBlockedReason($nid)
    {
        $fakeNid = self::where("nid", $nid)->where("is_blocked", true)->first();
        return $fakeNid ? $fakeNid->reason : null;
    }

    /**
     * Check if NID is already verified/used by someone
     */
    public static function isVerified($nid)
    {
        return self::where("nid", $nid)->where("is_verified", true)->exists();
    }

    /**
     * Check if NID is available for verification (not blocked and not already verified)
     */
    public static function isAvailableForVerification($nid)
    {
        $record = self::where("nid", $nid)->first();
        
        if (!$record) {
            return false; // NID doesn't exist in our database
        }
        
        return !$record->is_blocked && !$record->is_verified;
    }

    /**
     * Mark NID as verified
     */
    public static function markAsVerified($nid, $verifiedByUserId)
    {
        return self::where("nid", $nid)
                   ->where("is_blocked", false)
                   ->where("is_verified", false)
                   ->update([
                       'is_verified' => true,
                       'verified_at' => now(),
                       'verified_by' => $verifiedByUserId
                   ]);
    }

    /**
     * Get NID details if available for verification
     */
    public static function getNidDetails($nid)
    {
        return self::where("nid", $nid)->first();
    }

    /**
     * Get verification status information
     */
    public static function getVerificationStatus($nid)
    {
        $record = self::where("nid", $nid)->first();
        
        if (!$record) {
            return ['status' => 'not_found', 'message' => 'NID not found in database'];
        }
        
        if ($record->is_blocked) {
            return ['status' => 'blocked', 'message' => $record->reason];
        }
        
        if ($record->is_verified) {
            return ['status' => 'already_verified', 'message' => 'NID already used for verification', 'verified_at' => $record->verified_at];
        }
        
        return ['status' => 'available', 'message' => 'NID available for verification', 'details' => $record];
    }

    /**
     * Relationship with User who verified this NID
     */
    public function verifiedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'verified_by');
    }
}
