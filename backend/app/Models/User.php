<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; // <- Spatie
use Illuminate\Database\Eloquent\Factories\HasFactory; // 1. لازم تعمل Import للـ Trait دي
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable, HasRoles,HasFactory; // add HasRoles

    protected $fillable = [
        'name',
        'email',
        'password',
        // you may keep 'role' column for quick read, but authoritative source is spatie tables
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //Relations
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    // public function projects()
    // {
    //     return $this->hasMany(Project::class, 'owner_id');
    // }

    // public function bids()
    // {
    //     return $this->hasMany(Bid::class, 'freelancer_id');
    // }

    // Helper convenience if you keep role column
    public function isAdmin(): bool
    {
        // prefer Spatie check, fallback to column
        return $this->hasRole('admin') || $this->role === 'admin';
    }

    public function isClient(): bool
    {
        return $this->hasRole('client') || $this->role === 'client';
    }

    public function isFreelancer(): bool
    {
        return $this->hasRole('freelancer') || $this->role === 'freelancer';
    }
}
