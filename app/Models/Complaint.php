<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'assigned_to',
        'title',
        'description',
        'location',
        'image',
        'status',
        'priority',
        'resolution_notes',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(ComplaintCategory::class, 'category_id');
    }

    public function assignedWarden()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Status Scopes
    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Status Helpers
    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    // Status Badge Color
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'processing' => 'warning',
            'in_progress' => 'info',
            'completed' => 'success',
            default => 'secondary',
        };
    }

    // Priority Badge Color
    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'success',
            'medium' => 'warning',
            'high' => 'danger',
            default => 'secondary',
        };
    }
}
