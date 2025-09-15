<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user for authentication if needed
        $this->user = User::factory()->create();
    }

    #[Test]
    public function it_can_get_all_users()
    {
        // Create some test users
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'message'
            ]);

        $this->assertCount(4, $response->json('data')); // 3 + 1 from setUp
    }

    #[Test]
    public function it_can_create_a_new_user()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at'
                ],
                'message'
            ]);

        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email']
        ]);
    }

    #[Test]
    public function it_can_show_a_specific_user()
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at'
                ],
                'message'
            ])
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ]);
    }

    #[Test]
    public function it_can_update_a_user()
    {
        $user = User::factory()->create();
        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ];

        $response = $this->putJson("/api/users/{$user->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at'
                ],
                'message'
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $updateData['name'],
            'email' => $updateData['email']
        ]);
    }

    #[Test]
    public function it_can_delete_a_user()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User deleted successfully'
            ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }

    #[Test]
    public function it_returns_404_for_nonexistent_user()
    {
        $response = $this->getJson('/api/users/999');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'User not found'
            ]);
    }

    #[Test]
    public function it_validates_user_creation_data()
    {
        $response = $this->postJson('/api/users', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    #[Test]
    public function it_validates_unique_email_on_creation()
    {
        $existingUser = User::factory()->create();

        $userData = [
            'name' => $this->faker->name,
            'email' => $existingUser->email, // Duplicate email
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function it_validates_unique_email_on_update()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $updateData = [
            'name' => 'Updated Name',
            'email' => $user2->email // Duplicate email
        ];

        $response = $this->putJson("/api/users/{$user1->id}", $updateData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function it_allows_same_email_for_same_user_on_update()
    {
        $user = User::factory()->create();

        $updateData = [
            'name' => 'Updated Name',
            'email' => $user->email // Same email should be allowed
        ];

        $response = $this->putJson("/api/users/{$user->id}", $updateData);

        $response->assertStatus(200);
    }
}
