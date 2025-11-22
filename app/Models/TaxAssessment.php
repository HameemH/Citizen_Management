<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'owner_id',
        'fiscal_year',
        'assessed_value_snapshot',
        'land_use_snapshot',
        'tax_rate',
        'tax_amount',
        'status',
        'due_date',
        'issued_at',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'assessed_value_snapshot' => 'decimal:2',
        'tax_rate' => 'decimal:4',
        'tax_amount' => 'decimal:2',
        'due_date' => 'date',
        'issued_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function payments()
    {
        return $this->hasMany(TaxPayment::class);
    }

    public function scopeOutstanding($query)
    {
        return $query->whereIn('status', ['issued', 'overdue']);
    }

    public function markPaidIfSettled(): void
    {
        $paidTotal = $this->payments()->sum('amount');
        if ($paidTotal >= (float) $this->tax_amount) {
            $this->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
        }
    }
}
