<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\InteractsWithMedia;
class Profile extends Model implements HasMedia
{
  use InteractsWithMedia;
    protected $fillable = [
        'user_id',
        'title',
        'bio',
        'hourly_rate',
        'avatar_path',
        'country',
        'timezone',
        'avg_rating',
    ];

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class)
            ->withTimestamps();
    }
    

    public function projectsAsFreelancer()
    {
        return $this->hasMany(Project::class, 'freelancer_id');
    }

    // Helpers
    public function isComplete(): bool
    {
        // Simple rule: bio + at least 1 skill + hourly_rate set
        return ! empty($this->bio)
            && $this->skills()->count() > 0
            && ! is_null($this->hourly_rate);
    }

    public function completionPercentage(): int
    {
        $total = 3;
        $score = 0;
        if (! empty($this->bio)) $score++;
        if ($this->skills()->count() > 0) $score++;
        if (! is_null($this->hourly_rate)) $score++;
        return (int) round(($score / $total) * 100);
    }

    public function avatarUrl(): ?string
    {
        if (! $this->avatar_path) {
            return null;
        }
        return Storage::disk(config('filesystems.default'))->url($this->avatar_path);
    }
}
