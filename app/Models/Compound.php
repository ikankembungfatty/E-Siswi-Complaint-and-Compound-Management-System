<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compound extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'issued_by',
        'violation_type',
        'description',
        'amount',
        'due_date',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Check if compound is paid
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    // Check if compound is overdue
    public function isOverdue(): bool
    {
        return $this->status === 'unpaid' && $this->due_date < now();
    }

    // Status Badge Color
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'unpaid' => 'warning',
            'paid' => 'success',
            'overdue' => 'danger',
            default => 'secondary',
        };
    }

    // Scopes
    public function scopeUnpaid($query)
    {
        return $query->where('status', 'unpaid');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }
}
