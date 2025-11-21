<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'title',
        'type',
        'address_line',
        'city',
        'state',
        'postal_code',
        'area_sqft',
        'is_active',
        'is_available_for_rent',
        'rent_price',
        'assessed_value',
        'land_use',
        'last_valuation_at',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_available_for_rent' => 'boolean',
        'rent_price' => 'decimal:2',
        'area_sqft' => 'decimal:2',
        'assessed_value' => 'decimal:2',
        'last_valuation_at' => 'datetime',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function rentalRequests()
    {
        return $this->hasMany(RentalRequest::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}