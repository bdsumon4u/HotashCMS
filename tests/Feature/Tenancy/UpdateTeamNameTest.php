<?php

namespace Tests\Feature\Tenancy;

use App\Models\User;
use Tests\RefreshDatabaseWithTenant;
use Tests\TestCase;

class UpdateTeamNameTest extends TestCase
{
    use RefreshDatabaseWithTenant;

    public function test_team_names_can_be_updated()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $response = $this->put('/teams/'.$user->currentTeam->id, [
            'name' => 'Test Team',
        ]);

        $this->assertCount(1, $user->fresh()->ownedTeams);
        $this->assertEquals('Test Team', $user->currentTeam->fresh()->name);
    }
}
