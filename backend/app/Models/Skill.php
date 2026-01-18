<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Skill extends Model
{
    protected $fillable = ['name', 'slug'];

    protected static function booted()
    {
        static::creating(function ($skill) {
            if (empty($skill->slug)) {
                $skill->slug = Str::slug($skill->name);
            }
        });
    }

    public function profiles()
    {
        return $this->belongsToMany(Profile::class)
            ->withTimestamps();
    }
    

    public function scopeSearchByName($query, $term)
    {
        return $query->where('name', 'like', '%' . $term . '%');
    }
}
