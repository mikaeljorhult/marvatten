<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testVisitorCanNotAccessIndex()
    {
        $response = $this->get(route('users.index'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function testUserCanAccessIndex()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('users.index'));

        $response
            ->assertOk()
            ->assertSeeText($user->name);
    }

    /** @test */
    public function testUserCanAccessCreate()
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('users.create'));

        $response->assertOk();
    }

    /** @test */
    public function testVisitorCanNotCreateUser()
    {
        $response = $this
            ->post(route('users.store', [
                'name'  => 'First Last',
                'email' => 'firstlast@example.com',
            ]));

        $response->assertRedirect(route('login'));

        $this->assertDatabaseCount('users', 0);
    }

    /** @test */
    public function testUserCanCreateUser()
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->post(route('users.store', [
                'name'                  => 'First Last',
                'email'                 => 'firstlast@example.com',
                'password'              => 'password',
                'password_confirmation' => 'password',
            ]));

        $response
            ->assertRedirect(route('users.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', ['name' => 'First Last']);
    }

    /** @test */
    public function testNameIsRequiredToCreate()
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('users.create'))
            ->post(route('users.store', [
                'email'                 => 'firstlast@example.com',
                'password'              => 'password',
                'password_confirmation' => 'password',
            ]));

        $response
            ->assertRedirect(route('users.create'))
            ->assertSessionHasErrors(['name']);

        $this->assertDatabaseMissing('users', ['email' => 'firstlast@example.com']);
    }

    /** @test */
    public function testEmailMustBeUniqueToCreate()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('users.create'))
            ->post(route('users.store', [
                'name'                  => $user->name,
                'email'                 => $user->email,
                'password'              => 'password',
                'password_confirmation' => 'password',
            ]));

        $response
            ->assertRedirect(route('users.create'))
            ->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function testUserCanAccessUser()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('users.show', [$user]));

        $response
            ->assertOk()
            ->assertSeeText($user->name)
            ->assertSee(route('users.destroy', [$user]));
    }

    /** @test */
    public function testVisitorCanNotDeleteUser()
    {
        $user = User::factory()->create();

        $response = $this->delete(route('users.destroy', [$user]));

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('users', 1);
    }

    /** @test */
    public function testUserCanDeleteUser()
    {
        $user = User::factory()->create(['name' => 'First Last']);

        $response = $this
            ->actingAs(User::factory()->create())
            ->delete(route('users.destroy', [$user]));

        $response
            ->assertRedirect(route('users.index'))
            ->assertDontSeeText($user->name);

        $this->assertDatabaseMissing('users', ['name' => 'First Last']);
    }
}
