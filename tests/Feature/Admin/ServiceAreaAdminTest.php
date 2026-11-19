<?php

namespace Tests\Feature\Admin;

use App\Models\ServiceArea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceAreaAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->admin()->create());
    }

    public function test_index_lists_service_areas(): void
    {
        ServiceArea::factory()->create(['city' => 'Alpharetta']);

        $this->get('/admin/service-areas')->assertOk()->assertSee('Alpharetta');
    }

    public function test_admin_can_add_a_service_area(): void
    {
        $this->post('/admin/service-areas', [
            'city' => 'Atlanta',
            'county' => 'Fulton',
            'state' => 'Georgia',
            'description' => 'Intown homes.',
            'is_primary' => '1',
        ])->assertRedirect('/admin/service-areas')->assertSessionHas('status');

        $serviceArea = ServiceArea::sole();
        $this->assertSame('Atlanta', $serviceArea->city);
        $this->assertTrue($serviceArea->is_primary);
    }

    public function test_admin_can_edit_a_service_area(): void
    {
        $serviceArea = ServiceArea::factory()->create(['city' => 'Atlanta', 'is_primary' => true]);

        $this->get("/admin/service-areas/{$serviceArea->id}/edit")->assertOk()->assertSee('Atlanta');

        $this->put("/admin/service-areas/{$serviceArea->id}", [
            'city' => 'Atlanta',
            'county' => 'DeKalb',
            'state' => 'Georgia',
            'is_primary' => '0',
        ])->assertRedirect('/admin/service-areas');

        $serviceArea->refresh();
        $this->assertSame('DeKalb', $serviceArea->county);
        $this->assertFalse($serviceArea->is_primary);
    }

    public function test_admin_can_delete_a_service_area(): void
    {
        $serviceArea = ServiceArea::factory()->create();

        $this->delete("/admin/service-areas/{$serviceArea->id}")->assertRedirect('/admin/service-areas');

        $this->assertModelMissing($serviceArea);
    }

    public function test_city_is_required(): void
    {
        $this->post('/admin/service-areas', ['city' => '', 'state' => 'Georgia', 'is_primary' => '0'])
            ->assertSessionHasErrors('city');
    }

    public function test_duplicate_city_in_same_state_is_rejected_with_a_message(): void
    {
        ServiceArea::factory()->create(['city' => 'Cumming', 'state' => 'Georgia']);

        $this->post('/admin/service-areas', ['city' => 'Cumming', 'state' => 'Georgia', 'is_primary' => '0'])
            ->assertSessionHasErrors(['city' => 'This city is already listed for that state.']);
    }

    public function test_saving_unchanged_city_does_not_trip_the_unique_rule(): void
    {
        $serviceArea = ServiceArea::factory()->create(['city' => 'Cumming', 'state' => 'Georgia']);

        $this->put("/admin/service-areas/{$serviceArea->id}", ['city' => 'Cumming', 'state' => 'Georgia', 'is_primary' => '1'])
            ->assertSessionHasNoErrors();
    }
}
