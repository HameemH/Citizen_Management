<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'user_id',
        'status',
        'message',
        'tenant_start_date',
        'tenant_end_date',
        'tenant_monthly_rent',
        'tenant_security_deposit',
        'owner_start_date',
        'owner_end_date',
        'owner_monthly_rent',
        'owner_security_deposit',
        'owner_notes',
        'ready_for_admin',
        'owner_confirmed_at',
        'owner_confirmed_by',
        'decision_note',
        'decided_by',
        'decided_at',
    ];

    protected $casts = [
        'decided_at' => 'datetime',
        'tenant_start_date' => 'date',
        'tenant_end_date' => 'date',
        'owner_start_date' => 'date',
        'owner_end_date' => 'date',
        'owner_confirmed_at' => 'datetime',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function decider()
    {
        return $this->belongsTo(User::class, 'decided_by');
    }

    public function ownerConfirmer()
    {
        return $this->belongsTo(User::class, 'owner_confirmed_by');
    }

    public function rentAgreement()
    {
        return $this->hasOne(RentAgreement::class);
    }
}