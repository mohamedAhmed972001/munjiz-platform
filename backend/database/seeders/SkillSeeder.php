<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skill;

class SkillSeeder extends Seeder
{
    public function run()
    {
        $skills = [
            'PHP', 'Laravel', 'JavaScript', 'React', 'Vue.js', 'HTML', 'CSS',
            'MySQL', 'Postgres', 'Docker', 'AWS', 'Node.js', 'Tailwind CSS'
        ];

        foreach ($skills as $s) {
            Skill::firstOrCreate(['name' => $s], ['slug' => \Illuminate\Support\Str::slug($s)]);
        }
    }
}
