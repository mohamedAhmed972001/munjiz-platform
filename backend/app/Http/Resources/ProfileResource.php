<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var \App\Models\Profile $profile */
        $profile = $this->resource;

        return [
            'id' => $profile->id,
            'user' => [
                'id' => $profile->user->id,
                'name' => $profile->user->name,
            ],
            'title' => $profile->title,
            'bio' => $profile->bio,
            
            // 1. لا يظهر سعر الساعة إلا للفريلانسر فقط
            'hourly_rate' => $this->when($profile->user->hasRole('freelancer'), $profile->hourly_rate),
            
            'avatar_url' => $profile->avatarUrl(),
            'country' => $profile->country,
            'timezone' => $profile->timezone,

            // 2. لا تظهر المهارات إلا للفريلانسر فقط
            'skills' => $this->when($profile->user->hasRole('freelancer'), function() use ($profile) {
              // السطر ده بينادي الـ SkillResource لكل مهارة أوتوماتيك
              return SkillResource::collection($profile->skills);
          }),

            'avg_rating' => $profile->avg_rating,
            'completion_percentage' => $profile->completionPercentage(),
            'created_at' => $profile->created_at,
            'updated_at' => $profile->updated_at,
        ];
    }
}