<?php

namespace Tests\Feature\Tenancy;

use App\Models\User;
use Tests\RefreshDatabaseWithTenant;
use Tests\TestCase;

class ProfileInformationTest extends TestCase
{
    use RefreshDatabaseWithTenant;

    public function test_profile_information_can_be_updated()
    {
        $this->actingAs($user = User::factory()->create());

        $response = $this->put('/user/profile-information', [
            'name' => 'Test Name',
            'email' => 'test@example.com',
        ]);

        $this->assertEquals('Test Name', $user->fresh()->name);
        $this->assertEquals('test@example.com', $user->fresh()->email);
    }
}
