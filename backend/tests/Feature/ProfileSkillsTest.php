<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileSkillsTest extends TestCase
{
    use RefreshDatabase;

    public function test_attach_and_detach_skills_to_profile()
    {
        // seed skills
        $this->seed(\Database\Seeders\SkillSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('freelancer');
        $user->profile()->create();

        $skills = Skill::take(3)->pluck('id')->toArray();

        // acting as session-based user
        $this->actingAs($user, 'sanctum');

        // attach multiple
        $resp = $this->postJson('/api/profile/me/skills', ['skills' => $skills]);
        $resp->assertStatus(200);
        $this->assertEquals(3, $user->profile->skills()->count());

        // detach one
        $skillToDetach = $skills[0];
        $resp2 = $this->deleteJson("/api/profile/me/skills/{$skillToDetach}");
        $resp2->assertStatus(200);
        $this->assertEquals(2, $user->profile->fresh()->skills()->count());
    }

    public function test_skills_search_endpoint()
    {
        Skill::factory()->create(['name' => 'Laravel']);
        Skill::factory()->create(['name' => 'ReactJS']);
        Skill::factory()->create(['name' => 'Docker']);

        $resp = $this->getJson('/api/skills?q=Lar');
        $resp->assertStatus(200)
             ->assertJsonFragment(['name' => 'Laravel']);
    }
}
