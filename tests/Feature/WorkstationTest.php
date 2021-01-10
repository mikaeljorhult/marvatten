<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Workstation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkstationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testVisitorCanNotAccessIndex()
    {
        $response = $this->get(route('workstations.index'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function testUserCanAccessIndex()
    {
        $workstation = Workstation::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('workstations.index'));

        $response
            ->assertOk()
            ->assertSeeText($workstation->name);
    }

    /** @test */
    public function testUserCanAccessCreate()
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('workstations.create'));

        $response->assertOk();
    }

    /** @test */
    public function testVisitorCanNotCreateWorkstation()
    {
        $response = $this
            ->post(route('workstations.store', [
                'name' => 'Workstation 01',
                'serial' => 'AAABBBCCC',
            ]));

        $response->assertRedirect(route('login'));

        $this->assertDatabaseCount('workstations', 0);
    }

    /** @test */
    public function testUserCanCreateWorkstation()
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->post(route('workstations.store', [
                'name' => 'Workstation 01',
                'serial' => 'AAABBBCCC',
            ]));

        $response
            ->assertRedirect(route('workstations.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('workstations', ['name' => 'Workstation 01']);
    }

    /** @test */
    public function testNameIsRequiredToCreate()
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('workstations.create'))
            ->post(route('workstations.store', [
                'serial' => 'AAABBBCCC',
            ]));

        $response
            ->assertRedirect(route('workstations.create'))
            ->assertSessionHasErrors(['name']);

        $this->assertDatabaseCount('workstations', 0);
    }

    /** @test */
    public function testUserCanAccessWorkstation()
    {
        $workstation = Workstation::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('workstations.show', [$workstation]));

        $response
            ->assertOk()
            ->assertSeeText($workstation->name)
            ->assertSee(route('workstations.destroy', [$workstation]));
    }

    /** @test */
    public function testUserCanAccessEdit()
    {
        $workstation = Workstation::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('workstations.edit', [$workstation]));

        $response
            ->assertOk()
            ->assertSeeText($workstation->name)
            ->assertSee(route('workstations.update', [$workstation]));
    }

    /** @test */
    public function testUserCanUpdateWorkstation()
    {
        $workstation = Workstation::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('workstations.edit', [$workstation]))
            ->put(route('workstations.update', [$workstation]), [
                'name' => 'Workstation Updated',
                'serial' => 'AAABBBCCC',
            ]);

        $response
            ->assertRedirect(route('workstations.show', [$workstation]))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('workstations', ['name' => 'Workstation Updated']);
    }

    /** @test */
    public function testNameIsRequiredToUpdate()
    {
        $workstation = Workstation::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('workstations.edit', [$workstation]))
            ->put(route('workstations.update', [$workstation]), [
                'serial' => 'AAABBBCCC',
            ]);

        $response
            ->assertRedirect(route('workstations.edit', [$workstation]))
            ->assertSessionHasErrors(['name']);

        $this->assertDatabaseHas('workstations', ['name' => $workstation->name]);
    }

    /** @test */
    public function testSerialIsRequiredToUpdate()
    {
        $workstation = Workstation::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('workstations.edit', [$workstation]))
            ->put(route('workstations.update', [$workstation]), [
                'name' => 'Workstation Updated',
            ]);

        $response
            ->assertRedirect(route('workstations.edit', [$workstation]))
            ->assertSessionHasErrors(['serial']);

        $this->assertDatabaseHas('workstations', ['name' => $workstation->name]);
    }

    /** @test */
    public function testVisitorCanNotDeleteWorkstation()
    {
        $workstation = Workstation::factory()->create();

        $response = $this->delete(route('workstations.destroy', [$workstation]));

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('workstations', 1);
    }

    /** @test */
    public function testUserCanDeleteWorkstation()
    {
        $workstation = Workstation::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->delete(route('workstations.destroy', [$workstation]));

        $response
            ->assertRedirect(route('workstations.index'))
            ->assertDontSeeText($workstation->name);

        $this->assertDatabaseCount('workstations', 0);
    }
}
