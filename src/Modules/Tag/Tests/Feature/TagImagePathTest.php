<?php

namespace Nasirkhan\ModuleManager\Modules\Tag\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Nasirkhan\ModuleManager\Modules\Tag\Models\Tag;
use Tests\TestCase;

class TagImagePathTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $user = User::whereId(1)->first();

        $this->actingAs($user);
    }

    public function test_storing_a_tag_image_persists_a_host_relative_path(): void
    {
        Storage::fake('public');

        $response = $this->post(route('backend.tags.store'), [
            'name' => 'Relative image path tag',
            'status' => 'Active',
            'image' => UploadedFile::fake()->image('cover.jpg'),
        ]);

        $response->assertRedirect('admin/tags');

        $tag = Tag::where('name', 'Relative image path tag')->firstOrFail();

        $this->assertNotEmpty($tag->image);
        $this->assertStringNotContainsString('://', $tag->image);
        $this->assertSame(parse_url($tag->image, PHP_URL_PATH), $tag->image);
    }
}
