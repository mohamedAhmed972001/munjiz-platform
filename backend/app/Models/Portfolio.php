<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Portfolio extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['profile_id', 'title', 'description', 'link'];

    // علاقة الـ Portfolio بالبروفايل
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}