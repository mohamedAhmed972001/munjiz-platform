<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            ['name' => 'Laravel', 'slug' => 'laravel'],
            ['name' => 'React JS', 'slug' => 'react-js'],
            ['name' => 'UI/UX Design', 'slug' => 'ui-ux'],
            ['name' => 'Mobile App Development', 'slug' => 'mobile-apps'],
        ];
    
        foreach ($skills as $skill) {
            \App\Models\Skill::create($skill);
        }
    }
}
