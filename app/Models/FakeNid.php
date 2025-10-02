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
        "is_blocked"
    ];

    protected $casts = [
        "date_of_birth" => "date",
        "is_blocked" => "boolean"
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
}
