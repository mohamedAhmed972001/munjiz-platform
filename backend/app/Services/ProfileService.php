<?php

namespace App\Services;

use App\Models\Profile;
use App\Models\Skill;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileService
{
    public function updateProfile(Profile $profile, array $data): Profile
    {
        // handle avatar separately if present
        if (isset($data['avatar']) && $data['avatar'] instanceof UploadedFile) {
            $path = $this->storeAvatar($profile, $data['avatar']);
            $profile->avatar_path = $path;
            unset($data['avatar']);
        }

        // sync skills if present
        if (isset($data['skills'])) {
            $profile->skills()->sync($data['skills']);
        }

        $profile->fill($data);
        $profile->save();

        return $profile->refresh();
    }

    protected function storeAvatar(Profile $profile, UploadedFile $file): string
    {
        $folder = 'avatars/' . $profile->user_id;
        $filename = Str::random(12) . '.' . $file->getClientOriginalExtension();
        // store in public disk (configurable)
        $path = $file->storeAs($folder, $filename, config('filesystems.default'));
        return $path;
    }
}
