<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VersionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testVersionsAreDisplayedOnApplication()
    {
        $application = Application::factory()
            ->hasVersions(1, ['name' => '2021.1'])
            ->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('applications.show', [$application]));


        $response
            ->assertSee('2021.1')
            ->assertSee(route('applications.versions.store', [$application]));
    }

    /** @test */
    public function testVisitorCanNotCreateVersion()
    {
        $application = Application::factory()->create();

        $response = $this
            ->post(route('applications.versions.store', [$application]), [
                'name' => '2021.1',
            ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseCount('versions', 0);
    }

    /** @test */
    public function testUserCanCreateVersion()
    {
        $application = Application::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->post(route('applications.versions.store', [$application]), [
                'name' => '2021.1',
            ]);

        $response
            ->assertRedirect(route('applications.show', [$application]))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('versions', ['name' => '2021.1']);
    }

    /** @test */
    public function testNameIsRequiredToCreate()
    {
        $application = Application::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('applications.show', [$application]))
            ->post(route('applications.versions.store', [$application]), []);

        $response
            ->assertRedirect(route('applications.show', [$application]))
            ->assertSessionHasErrors(['name']);

        $this->assertDatabaseCount('versions', 0);
    }

    /** @test */
    public function testVisitorCanNotDeleteVersion()
    {
        $application = Application::factory()
            ->hasVersions(1, ['name' => '2021.1'])
            ->create();

        $response = $this->delete(route('applications.versions.destroy', [$application, $application->versions->first()]));

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('versions', 1);
    }

    /** @test */
    public function testUserCanDeleteWorkstation()
    {
        $application = Application::factory()
            ->hasVersions(1, ['name' => '2021.1'])
            ->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->delete(route('applications.versions.destroy', [$application, $application->versions->first()]));

        $response
            ->assertRedirect(route('applications.show', [$application]))
            ->assertDontSeeText($application->versions->first()->name);

        $this->assertDatabaseCount('versions', 0);
    }
}
