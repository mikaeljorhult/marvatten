<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testVisitorCanNotAccessIndex()
    {
        $response = $this->get(route('applications.index'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function testUserCanAccessIndex()
    {
        $application = Application::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('applications.index'));

        $response
            ->assertOk()
            ->assertSeeText($application->name);
    }

    /** @test */
    public function testUserCanAccessCreate()
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('applications.create'));

        $response->assertOk();
    }

    /** @test */
    public function testVisitorCanNotCreateApplication()
    {
        $response = $this
            ->post(route('applications.store', [
                'name' => 'Application 01'
            ]));

        $response->assertRedirect(route('login'));

        $this->assertDatabaseCount('applications', 0);
    }

    /** @test */
    public function testUserCanCreateApplication()
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->post(route('applications.store', [
                'name' => 'Application 01'
            ]));

        $response
            ->assertRedirect(route('applications.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('applications', ['name' => 'Application 01']);
    }

    /** @test */
    public function testNameIsRequiredToCreate()
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('applications.create'))
            ->post(route('applications.store', []));

        $response
            ->assertRedirect(route('applications.create'))
            ->assertSessionHasErrors(['name']);

        $this->assertDatabaseCount('applications', 0);
    }

    /** @test */
    public function testUserCanAccessApplication()
    {
        $application = Application::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('applications.show', [$application]));

        $response
            ->assertOk()
            ->assertSeeText($application->name)
            ->assertSee(route('applications.destroy', [$application]));
    }

    /** @test */
    public function testUserCanAccessEdit()
    {
        $application = Application::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('applications.edit', [$application]));

        $response
            ->assertOk()
            ->assertSeeText($application->name)
            ->assertSee(route('applications.update', [$application]));
    }

    /** @test */
    public function testUserCanUpdateApplication()
    {
        $application = Application::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('applications.edit', [$application]))
            ->put(route('applications.update', [$application]), [
                'name' => 'Application Updated',
            ]);

        $response
            ->assertRedirect(route('applications.show', [$application]))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('applications', ['name' => 'Application Updated']);
    }

    /** @test */
    public function testNameIsRequiredToUpdate()
    {
        $application = Application::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('applications.edit', [$application]))
            ->put(route('applications.update', [$application]), []);

        $response
            ->assertRedirect(route('applications.edit', [$application]))
            ->assertSessionHasErrors(['name']);

        $this->assertDatabaseHas('applications', ['name' => $application->name]);
    }

    /** @test */
    public function testVisitorCanNotDeleteApplication()
    {
        $application = Application::factory()->create();

        $response = $this->delete(route('applications.destroy', [$application]));

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('applications', 1);
    }

    /** @test */
    public function testUserCanDeleteApplication()
    {
        $application = Application::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->delete(route('applications.destroy', [$application]));

        $response
            ->assertRedirect(route('applications.index'))
            ->assertDontSeeText($application->name);

        $this->assertDatabaseCount('applications', 0);
    }
}
