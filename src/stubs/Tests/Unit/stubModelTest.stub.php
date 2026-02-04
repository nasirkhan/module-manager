<?php

namespace {{namespace}}\{{moduleName}}\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use {{namespace}}\{{moduleName}}\Models\{{moduleName}};

class {{moduleName}}ModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_{{moduleNameLower}}_has_correct_table_name(): void
    {
        ${{moduleNameLower}} = new {{moduleName}}();

        $this->assertEquals('{{moduleNameLowerPlural}}', ${{moduleNameLower}}->getTable());
    }

    public function test_{{moduleNameLower}}_has_correct_casts(): void
    {
        ${{moduleNameLower}} = new {{moduleName}}();
        $casts = ${{moduleNameLower}}->getCasts();

        $this->assertArrayHasKey('created_at', $casts);
        $this->assertArrayHasKey('updated_at', $casts);
        $this->assertArrayHasKey('deleted_at', $casts);
    }

    public function test_{{moduleNameLower}}_uses_soft_deletes(): void
    {
        ${{moduleNameLower}} = {{moduleName}}::factory()->create();
        ${{moduleNameLower}}->delete();

        $this->assertSoftDeleted('{{moduleNameLowerPlural}}', ['id' => ${{moduleNameLower}}->id]);
    }

    public function test_{{moduleNameLower}}_factory_creates_valid_data(): void
    {
        ${{moduleNameLower}} = {{moduleName}}::factory()->create();

        $this->assertNotEmpty(${{moduleNameLower}}->name);
        $this->assertDatabaseHas('{{moduleNameLowerPlural}}', [
            'id' => ${{moduleNameLower}}->id,
            'name' => ${{moduleNameLower}}->name,
        ]);
    }
}
