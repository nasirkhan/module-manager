<?php

namespace Nasirkhan\ModuleManager\Modules\Category\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Nasirkhan\ModuleManager\Modules\Category\Models\Category;
use Tests\TestCase;

class CategoryImagePathTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $user = User::whereId(1)->first();

        $this->actingAs($user);
    }

    public function test_storing_a_category_image_persists_a_host_relative_path(): void
    {
        Storage::fake('public');

        $response = $this->post(route('backend.categories.store'), [
            'name' => 'Relative image path category',
            'status' => 'Active',
            'image' => UploadedFile::fake()->image('cover.jpg'),
        ]);

        $response->assertRedirect('admin/categories');

        $category = Category::where('name', 'Relative image path category')->firstOrFail();

        $this->assertNotEmpty($category->image);
        $this->assertStringNotContainsString('://', $category->image);
        $this->assertSame(parse_url($category->image, PHP_URL_PATH), $category->image);
    }
}
