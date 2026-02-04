<?php

namespace {{namespace}}\{{moduleName}}\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Builders\UserBuilder;
use Tests\TestCase;

class {{moduleName}}BackendTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = UserBuilder::make()->asAdmin()->create();
        $this->actingAs($this->admin);
    }

    public function test_admin_can_view_{{moduleNameLower}}_index(): void
    {
        $response = $this->get('/admin/{{moduleNameLowerPlural}}');

        $response->assertStatus(200);
        $response->assertSee('{{moduleNamePlural}}');
    }

    public function test_admin_can_create_{{moduleNameLower}}(): void
    {
        $data = [
            'name' => 'Test {{moduleName}}',
            'description' => 'Test description',
            'status' => 1,
        ];

        $response = $this->post('/admin/{{moduleNameLowerPlural}}', $data);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('{{moduleNameLowerPlural}}', ['name' => 'Test {{moduleName}}']);
    }

    public function test_admin_can_view_{{moduleNameLower}}(): void
    {
        ${{moduleNameLower}} = \{{namespace}}\{{moduleName}}\Models\{{moduleName}}::factory()->create();

        $response = $this->get("/admin/{{moduleNameLowerPlural}}/{${{moduleNameLower}}->id}");

        $response->assertStatus(200);
        $response->assertSee(${{moduleNameLower}}->name);
    }

    public function test_admin_can_edit_{{moduleNameLower}}(): void
    {
        ${{moduleNameLower}} = \{{namespace}}\{{moduleName}}\Models\{{moduleName}}::factory()->create();

        $response = $this->get("/admin/{{moduleNameLowerPlural}}/{${{moduleNameLower}}->id}/edit");

        $response->assertStatus(200);
        $response->assertSee(${{moduleNameLower}}->name);
    }

    public function test_admin_can_update_{{moduleNameLower}}(): void
    {
        ${{moduleNameLower}} = \{{namespace}}\{{moduleName}}\Models\{{moduleName}}::factory()->create();

        $data = [
            'name' => 'Updated {{moduleName}}',
            'description' => 'Updated description',
            'status' => 1,
        ];

        $response = $this->put("/admin/{{moduleNameLowerPlural}}/{${{moduleNameLower}}->id}", $data);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('{{moduleNameLowerPlural}}', ['name' => 'Updated {{moduleName}}']);
    }

    public function test_admin_can_delete_{{moduleNameLower}}(): void
    {
        ${{moduleNameLower}} = \{{namespace}}\{{moduleName}}\Models\{{moduleName}}::factory()->create();

        $response = $this->delete("/admin/{{moduleNameLowerPlural}}/{${{moduleNameLower}}->id}");

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertSoftDeleted('{{moduleNameLowerPlural}}', ['id' => ${{moduleNameLower}}->id]);
    }

    public function test_admin_can_restore_{{moduleNameLower}}(): void
    {
        ${{moduleNameLower}} = \{{namespace}}\{{moduleName}}\Models\{{moduleName}}::factory()->create();
        ${{moduleNameLower}}->delete();

        $response = $this->patch("/admin/{{moduleNameLowerPlural}}/{${{moduleNameLower}}->id}/restore");

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('{{moduleNameLowerPlural}}', [
            'id' => ${{moduleNameLower}}->id,
            'deleted_at' => null,
        ]);
    }
}
