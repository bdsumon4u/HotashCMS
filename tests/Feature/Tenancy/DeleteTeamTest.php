<?php

namespace Tests\Feature\Tenancy;

use App\Models\Team;
use App\Models\User;
use Tests\RefreshDatabaseWithTenant;
use Tests\TestCase;

class DeleteTeamTest extends TestCase
{
    use RefreshDatabaseWithTenant;

    public function test_teams_can_be_deleted()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $user->ownedTeams()->save($team = Team::factory()->make([
            'personal_team' => false,
        ]));

        $team->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'test-role']
        );

        $response = $this->delete('/teams/'.$team->id);

        $this->assertNull($team->fresh());
        $this->assertCount(0, $otherUser->fresh()->teams);
    }

    public function test_personal_teams_cant_be_deleted()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $response = $this->delete('/teams/'.$user->currentTeam->id);

        $this->assertNotNull($user->currentTeam->fresh());
    }
}
