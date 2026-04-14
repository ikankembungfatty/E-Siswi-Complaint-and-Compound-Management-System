<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'student_id',
        'phone',
        'block',
        'room',
        'room_level',
        'specialization',
        'is_available',
        'two_factor_secret',
        'two_factor_enabled',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_available' => 'boolean',
        'two_factor_enabled' => 'boolean',
    ];

    // Relationships
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function assignedComplaints()
    {
        return $this->hasMany(Complaint::class, 'assigned_to');
    }

    public function compounds()
    {
        return $this->hasMany(Compound::class);
    }

    public function issuedCompounds()
    {
        return $this->hasMany(Compound::class, 'issued_by');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }



    // Role Check Helpers
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isWarden(): bool
    {
        return $this->role === 'warden';
    }

    public function isHepaStaff(): bool
    {
        return $this->role === 'hepa_staff';
    }



    public function isAdmin(): bool
    {
        return $this->isWarden() || $this->isHepaStaff();
    }

    public function hasTwoFactorEnabled(): bool
    {
        return $this->two_factor_enabled && !empty($this->two_factor_secret);
    }

    // Scopes
    public function scopeStudents($query)
    {
        return $query->where('role', 'student');
    }

    public function scopeWardens($query)
    {
        return $query->where('role', 'warden');
    }

}
