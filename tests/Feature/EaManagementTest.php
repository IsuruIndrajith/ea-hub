<?php

namespace Tests\Feature;

use App\Models\Ea;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Filament\Panel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EaManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('The SQLite PDO driver is required for this test suite.');
        }

        parent::setUp();
    }

    public function test_ea_slug_and_keyword_fields_are_persisted(): void
    {
        $ea = Ea::factory()->create([
            'name' => 'Atlas Trend EA',
            'version' => '2.1',
            'primary_keywords' => ['trend trading'],
        ]);

        $this->assertSame('atlas-trend-ea-21', $ea->slug);
        $this->assertSame(['trend trading'], $ea->fresh()->primary_keywords);
    }

    public function test_ea_can_be_created_with_only_its_name(): void
    {
        $ea = Ea::query()->create(['name' => 'Minimal EA']);

        $this->assertSame('Minimal EA', $ea->fresh()->name);
        $this->assertNull($ea->fresh()->version);
        $this->assertNull($ea->fresh()->vendor_url);
        $this->assertNull($ea->fresh()->primary_keywords);
        $this->assertCount(0, $ea->signals);
    }

    public function test_only_active_users_with_hub_roles_can_access_the_panel(): void
    {
        $this->seed(RoleSeeder::class);

        $editor = User::factory()->create(['is_active' => true]);
        $editor->assignRole('editor');
        $panel = \Mockery::mock(Panel::class);
        $this->assertTrue($editor->canAccessPanel($panel));

        $inactive = User::factory()->create(['is_active' => false]);
        $inactive->assignRole('admin');
        $this->assertFalse($inactive->canAccessPanel($panel));

        $unassigned = User::factory()->create(['is_active' => true]);
        $this->assertFalse($unassigned->canAccessPanel($panel));
    }
}
