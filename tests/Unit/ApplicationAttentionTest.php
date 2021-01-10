<?php

namespace Tests\Unit;

use App\Models\Application;
use App\Models\Version;
use App\Models\Workstation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationAttentionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testApplicationsNotInstalledDoesNotNeedAttention()
    {
        Application::factory()
                   ->hasVersions(1)
                   ->create(['seats' => 1]);

        $this->assertCount(0, Application::needsAttention()->get());
    }

    /** @test */
    public function testApplicationsExceedingAvailableSeatsNeedsAttention()
    {
        Application::factory()
                   ->hasVersions(1)
                   ->create(['seats' => 1]);

        Workstation::factory()
                   ->count(2)
                   ->afterCreating(function (Workstation $workstation) {
                       $workstation->versions()->attach(Version::first());
                   })
                   ->create();

        $needsAttention = Application::needsAttention()->get();

        $this->assertCount(1, $needsAttention);
    }

    /** @test */
    public function testFloatingApplicationsMayExceedSeats()
    {
        Application::factory()
                   ->hasVersions(1)
                   ->create(['seats' => 1, 'is_floating' => true]);

        Workstation::factory()
                   ->count(2)
                   ->afterCreating(function (Workstation $workstation) {
                       $workstation->versions()->attach(Version::first());
                   })
                   ->create();

        $needsAttention = Application::needsAttention()->get();

        $this->assertCount(0, $needsAttention);
    }

    /** @test */
    public function testAttributeDoesNotNeedAttentionWhenApplicationIsNotInstalled()
    {
        $application = Application::factory()
                                  ->hasVersions(1)
                                  ->create(['seats' => 1]);

        $this->assertFalse($application->needsAttention);
    }

    /** @test */
    public function testAttributeNeedAttentionWhenApplicationSeatsAreExceeded()
    {
        $application = Application::factory()
                                  ->hasVersions(1)
                                  ->create(['seats' => 1]);

        Workstation::factory()
                   ->count(2)
                   ->afterCreating(function (Workstation $workstation) {
                       $workstation->versions()->attach(Version::first());
                   })
                   ->create();

        $this->assertTrue($application->needsAttention);
    }

    /** @test */
    public function testAttributeDoesNotNeedAttentionWhenApplicationIsFloating()
    {
        $application = Application::factory()
                                  ->hasVersions(1)
                                  ->create(['seats' => 1, 'is_floating' => true]);

        Workstation::factory()
                   ->count(2)
                   ->afterCreating(function (Workstation $workstation) {
                       $workstation->versions()->attach(Version::first());
                   })
                   ->create();

        $this->assertFalse($application->needsAttention);
    }
}
