<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'credit_balance',
        'points',
        'active',
    ];

    protected $casts = [
        'credit_balance' => 'decimal:2',
        'active' => 'boolean',
    ];

    // Relationships
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    // Accessors
    public function getTotalPurchasesAttribute(): float
    {
        return $this->sales()->sum('total');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
