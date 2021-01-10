<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\User;
use App\Models\Workstation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkstationApplicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testVisitorCanNotAttachApplication()
    {
        $workstation = Workstation::factory()->create();
        $application = Application::factory()->create();

        $response = $this
            ->from(route('workstations.show', [$workstation]))
            ->post(route('workstations.applications.store', [$workstation]), [
                'application_id' => $application->id,
            ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseCount('application_workstation', 0);
    }

    /** @test */
    public function testUserCanAttachApplication()
    {
        $workstation = Workstation::factory()->create();
        $application = Application::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('workstations.show', [$workstation]))
            ->post(route('workstations.applications.store', [$workstation]), [
                'application_id' => $application->id,
            ]);

        $response
            ->assertRedirect(route('workstations.show', [$workstation]))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('application_workstation', [
            'application_id' => $application->id,
            'workstation_id' => $workstation->id,
        ]);
    }

    /** @test */
    public function testApplicationIsRequiredToCreate()
    {
        $workstation = Workstation::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('workstations.show', [$workstation]))
            ->post(route('workstations.applications.store', [$workstation]), []);

        $response
            ->assertRedirect(route('workstations.show', [$workstation]))
            ->assertSessionHasErrors(['application_id']);

        $this->assertDatabaseCount('application_workstation', 0);
    }

    /** @test */
    public function testApplicationMustExistToCreate()
    {
        $workstation = Workstation::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('workstations.show', [$workstation]))
            ->post(route('workstations.applications.store', [$workstation]), [
                'application_id' => '100',
            ]);

        $response
            ->assertRedirect(route('workstations.show', [$workstation]))
            ->assertSessionHasErrors(['application_id']);

        $this->assertDatabaseCount('application_workstation', 0);
    }

    /** @test */
    public function testUserCanDetachApplication()
    {
        $workstation = Workstation::factory()
                                  ->hasApplications(1)
                                  ->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('workstations.show', [$workstation]))
            ->delete(route('workstations.applications.destroy', [$workstation, $workstation->applications->first()]));

        $response
            ->assertRedirect(route('workstations.show', [$workstation]))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseCount('application_workstation', 0);
    }
}
