<?php

namespace Tests\Feature\Auth;

use App\Models\MembershipType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $membershipType = MembershipType::factory()->create();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'membership_type_id' => $membershipType->id
        ]);

        $user = User::where('email', 'test@example.com')->first();

        // Ensure that $user is not null and has the expected attributes
        $this->assertNotNull($user);
        $this->assertEquals('Test User', $user->name);
        $this->assertTrue(Hash::check('password', $user->password)); // Check if the password is hashed

        // Optionally log in the user manually if needed
        $this->actingAs($user);

        // Check if the user is authenticated
        $this->assertAuthenticated();

        // Ensure the user exists in the database
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);

        // Ensure the response redirects to the dashboard
        $response->assertRedirect(route('dashboard', [], false));
    }
}
