<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\User;
use App\Models\Version;
use App\Models\Workstation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkstationVersionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testEditLinkIsVisibleOnWorkstation()
    {
        $version = Version::factory()->create();
        $workstation = Workstation::factory()
                                  ->hasAttached($version->application)
                                  ->hasAttached($version)
                                  ->create();

        $response = $this->actingAs(User::factory()->create())
                         ->get(route('workstations.show', [$workstation]));

        $response
            ->assertOk()
            ->assertSeeText($version->application->name)
            ->assertSeeText($version->name)
            ->assertSee(route('workstations.applications.edit', [$workstation, $version->application]));
    }

    /** @test */
    public function testApplicationsAndVersionsAreListed()
    {
        $version = Version::factory()->create();
        $workstation = Workstation::factory()
                                  ->hasAttached($version->application)
                                  ->hasAttached($version)
                                  ->create();

        $response = $this->actingAs(User::factory()->create())
                         ->get(route('workstations.applications.edit', [$workstation, $version->application]));

        $response
            ->assertOk()
            ->assertSeeText($version->application->name)
            ->assertSeeText($version->name);
    }

    /** @test */
    public function testFormToAddVersionsIsDisplayed()
    {
        $workstation = Workstation::factory()
                                  ->hasAttached($application = Application::factory()->create())
                                  ->create();

        $response = $this->actingAs(User::factory()->create())
                         ->get(route('workstations.applications.edit', [$workstation, $application]));

        $response
            ->assertOk()
            ->assertSee(route('workstations.versions.store', [$workstation]));
    }

    /** @test */
    public function testVisitorCanNotAttachVersion()
    {
        $workstation = Workstation::factory()
                                  ->hasAttached($application = Application::factory()->hasVersions(1)->create())
                                  ->create();

        $response = $this
            ->from(route('workstations.applications.edit', [$workstation, $application]))
            ->post(route('workstations.versions.store', [$workstation]), [
                'version_id' => $application->versions->first()->id,
            ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseCount('version_workstation', 0);
    }

    /** @test */
    public function testUserCanAttachVersion()
    {
        $workstation = Workstation::factory()
                                  ->hasAttached($application = Application::factory()->hasVersions(1)->create())
                                  ->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('workstations.applications.edit', [$workstation, $application]))
            ->post(route('workstations.versions.store', [$workstation]), [
                'version_id' => $application->versions->first()->id,
            ]);

        $response
            ->assertRedirect(route('workstations.applications.edit', [$workstation, $application]))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('version_workstation', [
            'version_id'     => $application->versions->first()->id,
            'workstation_id' => $workstation->id,
        ]);
    }

    /** @test */
    public function testVersionIsRequiredToAttach()
    {
        $workstation = Workstation::factory()
                                  ->hasAttached($application = Application::factory()->hasVersions(1)->create())
                                  ->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('workstations.applications.edit', [$workstation, $application]))
            ->post(route('workstations.versions.store', [$workstation]), []);

        $response
            ->assertRedirect(route('workstations.applications.edit', [$workstation, $application]))
            ->assertSessionHasErrors(['version_id']);

        $this->assertDatabaseCount('version_workstation', 0);
    }

    /** @test */
    public function testVersionMustExistToAttach()
    {
        $workstation = Workstation::factory()
                                  ->hasAttached($application = Application::factory()->hasVersions(1)->create())
                                  ->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('workstations.applications.edit', [$workstation, $application]))
            ->post(route('workstations.versions.store', [$workstation]), [
                'version_id' => 100,
            ]);

        $response
            ->assertRedirect(route('workstations.applications.edit', [$workstation, $application]))
            ->assertSessionHasErrors(['version_id']);

        $this->assertDatabaseCount('version_workstation', 0);
    }

    /** @test */
    public function testDetachLinkIsDisplayed()
    {
        $version = Version::factory()->create();
        $workstation = Workstation::factory()
                                  ->hasAttached($version->application)
                                  ->hasAttached($version)
                                  ->create();

        $response = $this->actingAs(User::factory()->create())
                         ->get(route('workstations.applications.edit', [$workstation, $version->application]));

        $response
            ->assertOk()
            ->assertSee(route('workstations.versions.destroy', [$workstation, $version]));
    }

    /** @test */
    public function testVisitorCanNotDetachVersion()
    {
        $version = Version::factory()->create();
        $workstation = Workstation::factory()
                                  ->hasAttached($version->application)
                                  ->hasAttached($version)
                                  ->create();

        $response = $this
            ->from(route('workstations.applications.edit', [$workstation, $version->application]))
            ->delete(route('workstations.versions.destroy', [$workstation, $version]));

        $response
            ->assertRedirect('login')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseCount('version_workstation', 1);
    }

    /** @test */
    public function testUserCanDetachVersion()
    {
        $version = Version::factory()->create();
        $workstation = Workstation::factory()
                                  ->hasAttached($version->application)
                                  ->hasAttached($version)
                                  ->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('workstations.applications.edit', [$workstation, $version->application]))
            ->delete(route('workstations.versions.destroy', [$workstation, $version]));

        $response
            ->assertRedirect(route('workstations.applications.edit', [$workstation, $version->application]))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseCount('version_workstation', 0);
    }
}
