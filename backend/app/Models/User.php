<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active', // ضيف دي هنا عشان تقدر تتحكم فيها
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'roles', // بنخفي العلاقة الخام وبنبعت الـ role_name بدالها
    ];

    /**
     * Appends: بنضيف حقول "وهمية" تظهر لما نحول الموديل لـ JSON
     */
    protected $appends = ['role_name'];

    /**
     * Accessor: لجلب اسم الدور الحالي للمستخدم بسهولة
     * ده هيخلي الـ React يستلم حقل اسمه role_name فوراً
     */
    protected function roleName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->roles->first()?->name ?? 'no_role',
        );
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // ضيف الميثود دي جوه كلاس User
// app/Models/User.php
public function skills() {
  return $this->belongsToMany(Skill::class);
}
}