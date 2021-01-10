<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Workstation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkstationMetaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testMetaFieldsAreDisplayedOnShow()
    {
        $workstation = Workstation::factory()->withMeta()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('workstations.show', [$workstation]));

        $response
            ->assertOk()
            ->assertSeeText('Test Label')
            ->assertSeeText('Test Value');
    }

    /** @test */
    public function testMetaFieldsAreStored()
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->post(route('workstations.store', [
                'name'             => 'Workstation 01',
                'serial'           => 'AAABBBCCC',
                'fields[0][label]' => 'Test Label',
                'fields[0][value]' => 'Test Value',
            ]));

        $response
            ->assertRedirect(route('workstations.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('metas', [
            'metable_type' => 'App\\Models\\Workstation',
            'metable_id'   => 1,
            'label'        => 'Test Label',
            'value'        => 'Test Value',
        ]);
    }

    /** @test */
    public function testMetaFieldValueIsRequiredToCreate()
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('workstations.create'))
            ->post(route('workstations.store', [
                'name'             => 'Workstation 01',
                'serial'           => 'AAABBBCCC',
                'fields[0][label]' => 'Test Label',
            ]));

        $response
            ->assertRedirect(route('workstations.create'))
            ->assertSessionHasErrors(['fields.0.value']);

        $this->assertDatabaseCount('metas', 0);
    }

    /** @test */
    public function testMetaFieldLabelIsRequiredToCreate()
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('workstations.create'))
            ->post(route('workstations.store', [
                'name'             => 'Workstation 01',
                'serial'           => 'AAABBBCCC',
                'fields[0][value]' => 'Test Value',
            ]));

        $response
            ->assertRedirect(route('workstations.create'))
            ->assertSessionHasErrors(['fields.0.label']);

        $this->assertDatabaseCount('metas', 0);
    }

    /** @test */
    public function testMetaFieldsAreDisplayedOnEdit()
    {
        $workstation = Workstation::factory()->withMeta()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('workstations.edit', [$workstation]));

        $response
            ->assertOk()
            ->assertSee('Test Label')
            ->assertSee('Test Value');
    }

    /** @test */
    public function testMetaFieldsAreStoredOnUpdate()
    {
        $workstation = Workstation::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('workstations.edit', [$workstation]))
            ->put(route('workstations.update', [$workstation]), [
                'name'   => 'Workstation 01',
                'serial' => 'AAABBBCCC',
                'fields' => [
                    [
                        'label' => 'Test Label',
                        'value' => 'Test Value',
                    ],
                ],
            ]);

        $response
            ->assertRedirect(route('workstations.show', [$workstation]))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('metas', [
            'metable_type' => 'App\\Models\\Workstation',
            'metable_id'   => 1,
            'label'        => 'Test Label',
            'value'        => 'Test Value',
        ]);
    }

    /** @test */
    public function testMetaFieldsAreUpdatedOnUpdate()
    {
        $workstation = Workstation::factory()->withMeta()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('workstations.edit', [$workstation]))
            ->put(route('workstations.update', [$workstation]), [
                'name'   => 'Workstation 01',
                'serial' => 'AAABBBCCC',
                'fields' => [
                    [
                        'id'    => 1,
                        'label' => 'New Label',
                        'value' => 'New Value',
                    ],
                ],
            ]);

        $response
            ->assertRedirect(route('workstations.show', [$workstation]))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('metas', [
            'metable_type' => 'App\\Models\\Workstation',
            'metable_id'   => 1,
            'label'        => 'New Label',
            'value'        => 'New Value',
        ]);
    }

    /** @test */
    public function testMetaFieldLabelIsRequiredToUpdate()
    {
        $workstation = Workstation::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('workstations.edit', [$workstation]))
            ->put(route('workstations.update', [$workstation]), [
                'name'   => 'Workstation 01',
                'serial' => 'AAABBBCCC',
                'fields' => [
                    [
                        'value' => 'New Value',
                    ],
                ],
            ]);

        $response
            ->assertRedirect(route('workstations.edit', [$workstation]))
            ->assertSessionHasErrors(['fields.*.label']);

        $this->assertDatabaseCount('metas', 0);
    }

    /** @test */
    public function testMetaFieldValueIsRequiredToUpdate()
    {
        $workstation = Workstation::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('workstations.edit', [$workstation]))
            ->put(route('workstations.update', [$workstation]), [
                'name'   => 'Workstation 01',
                'serial' => 'AAABBBCCC',
                'fields' => [
                    [
                        'label' => 'New Label',
                    ],
                ],
            ]);

        $response
            ->assertRedirect(route('workstations.edit', [$workstation]))
            ->assertSessionHasErrors(['fields.*.value']);

        $this->assertDatabaseCount('metas', 0);
    }

    /** @test */
    public function testMissingMetaFieldsAreRemovedOnUpdate()
    {
        $workstation = Workstation::factory()->withMeta()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('workstations.edit', [$workstation]))
            ->put(route('workstations.update', [$workstation]), [
                'name'   => 'Workstation 01',
                'serial' => 'AAABBBCCC',
            ]);

        $response
            ->assertRedirect(route('workstations.show', [$workstation]))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseCount('metas', 0);
    }
}
