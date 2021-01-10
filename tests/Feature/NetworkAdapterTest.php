<?php

namespace Tests\Feature;

use App\Models\NetworkAdapter;
use App\Models\User;
use App\Models\Workstation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NetworkAdapterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testUserCanCreateAdapter()
    {
        $workstation = Workstation::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('workstations.show', [$workstation]))
            ->post(route('network-adapters.store', [
                'name'           => 'Ethernet 01',
                'mac_address'    => '01:23:45:67:89:0A',
                'workstation_id' => $workstation->id,
            ]));

        $response
            ->assertRedirect(route('workstations.show', [$workstation]))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('network_adapters', [
            'name'        => 'Ethernet 01',
            'mac_address' => '01234567890A',
        ]);
    }

    /** @test */
    public function testNameIsRequiredToCreate()
    {
        $workstation = Workstation::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('workstations.show', [$workstation]))
            ->post(route('network-adapters.store', [
                'mac_address'    => '01:23:45:67:89:0A',
                'workstation_id' => $workstation->id,
            ]));

        $response
            ->assertRedirect(route('workstations.show', [$workstation]))
            ->assertSessionHasErrors(['name']);

        $this->assertDatabaseCount('network_adapters', 0);
    }

    /** @test */
    public function testMacAddressIsRequiredToCreate()
    {
        $workstation = Workstation::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('workstations.show', [$workstation]))
            ->post(route('network-adapters.store', [
                'name'           => 'Ethernet 01',
                'workstation_id' => $workstation->id,
            ]));

        $response
            ->assertRedirect(route('workstations.show', [$workstation]))
            ->assertSessionHasErrors(['mac_address']);

        $this->assertDatabaseCount('network_adapters', 0);
    }

    /** @test */
    public function testMacAddressMustBeValidToCreate()
    {
        $workstation = Workstation::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('workstations.show', [$workstation]))
            ->post(route('network-adapters.store', [
                'name'           => 'Ethernet 01',
                'mac_address'    => 'NO:TV:AL:ID:NU:MB',
                'workstation_id' => $workstation->id,
            ]));

        $response
            ->assertRedirect(route('workstations.show', [$workstation]))
            ->assertSessionHasErrors(['mac_address']);

        $this->assertDatabaseCount('network_adapters', 0);
    }

    /** @test */
    public function testVisitorCanNotDeleteAdapter()
    {
        $adapter = NetworkAdapter::factory()->create();

        $response = $this
            ->from(route('workstations.show', [$adapter->workstation]))
            ->delete(route('network-adapters.destroy', [$adapter]));

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('network_adapters', 1);
    }

    /** @test */
    public function testUserCanDeleteAdapter()
    {
        $adapter = NetworkAdapter::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('workstations.show', [$adapter->workstation]))
            ->delete(route('network-adapters.destroy', [$adapter]));

        $response
            ->assertRedirect(route('workstations.show', [$adapter->workstation]))
            ->assertDontSeeText($adapter->name);

        $this->assertDatabaseCount('network_adapters', 0);
    }
}
