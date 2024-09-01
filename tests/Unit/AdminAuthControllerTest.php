<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_login_an_admin()
    {
        $admin = User::factory()->create();

        $response = $this->postJson('/api/admin/login',[
            'email' => $admin->email,
            'password' => 'password'
        ]);
        
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'message',
            'user',
            'access_token'
            ]);
    }

    /** @test */
    public function it_cannot_login_an_admin_with_invalid_credentials()
    {
        $admin = User::factory()->create();

        $response = $this->postJson('/api/admin/login', [
            'email' => $admin->email,
            'password' => 'invalid-password'
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Invalid credentials'
        ]);
    }

    /** @test */
    public function it_can_logout_an_admin()
    {
        $admin = User::factory()->create();

        $this->actingAs($admin, 'web');

        $response = $this->postJson('/api/admin/logout');

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Logged out successfully'
        ]);
    }
}
