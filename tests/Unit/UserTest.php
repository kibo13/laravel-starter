<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    #[Test]
    public function it_can_create_a_user()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123'
        ];

        $user = User::create($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($userData['name'], $user->name);
        $this->assertEquals($userData['email'], $user->email);
        $this->assertTrue(password_verify($userData['password'], $user->password));
    }

    #[Test]
    public function it_can_find_a_user_by_id()
    {
        $user = User::factory()->create();

        $foundUser = User::find($user->id);

        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($user->id, $foundUser->id);
        $this->assertEquals($user->name, $foundUser->name);
        $this->assertEquals($user->email, $foundUser->email);
    }

    #[Test]
    public function it_can_update_a_user()
    {
        $user = User::factory()->create();
        $originalName = $user->name;
        $originalEmail = $user->email;

        $user->update([
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ]);

        $this->assertNotEquals($originalName, $user->name);
        $this->assertNotEquals($originalEmail, $user->email);
        $this->assertEquals('Updated Name', $user->name);
        $this->assertEquals('updated@example.com', $user->email);
    }

    #[Test]
    public function it_can_delete_a_user()
    {
        $user = User::factory()->create();
        $userId = $user->id;

        $user->delete();

        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    #[Test]
    public function it_has_fillable_attributes()
    {
        $user = new User();
        $fillable = $user->getFillable();

        $this->assertContains('name', $fillable);
        $this->assertContains('email', $fillable);
        $this->assertContains('password', $fillable);
    }

    #[Test]
    public function it_has_hidden_attributes()
    {
        $user = new User();
        $hidden = $user->getHidden();

        $this->assertContains('password', $hidden);
        $this->assertContains('remember_token', $hidden);
    }

    #[Test]
    public function it_casts_attributes_correctly()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(\Carbon\Carbon::class, $user->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $user->updated_at);
        // email_verified_at can be null or Carbon instance depending on factory
        $this->assertTrue(
            $user->email_verified_at === null ||
                $user->email_verified_at instanceof \Carbon\Carbon
        );
    }
}
