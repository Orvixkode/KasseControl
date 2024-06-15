<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_no',
        'supplier_id',
        'user_id',
        'purchase_date',
        'total',
        'paid',
        'payment_method',
        'status',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'datetime',
        'total' => 'decimal:2',
        'paid' => 'decimal:2',
    ];

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    // Accessors
    public function getDueAttribute(): float
    {
        return $this->total - $this->paid;
    }

    // Scopes
    public function scopeReceived($query)
    {
        return $query->where('status', 'received');
    }
}
