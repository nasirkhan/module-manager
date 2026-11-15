<?php

namespace Nasirkhan\ModuleManager\Modules\Post\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Nasirkhan\ModuleManager\Modules\Post\Enums\PostStatus;
use Nasirkhan\ModuleManager\Modules\Post\Enums\PostType;
use Nasirkhan\ModuleManager\Modules\Post\Models\Post;
use Tests\TestCase;

class PostImagePathTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Artisan::call('laravel-starter:insert-demo-data');

        $user = User::whereId(1)->first();

        $this->actingAs($user);
    }

    public function test_image_path_from_url_strips_scheme_and_host(): void
    {
        $this->assertSame(
            '/posts/5/cover.jpg',
            Post::imagePathFromUrl('https://example.com/posts/5/cover.jpg')
        );
    }

    public function test_storing_a_post_image_persists_a_host_relative_path(): void
    {
        Storage::fake('public');

        $response = $this->post(route('backend.posts.store'), [
            'name' => 'Relative image path post',
            'intro' => 'Intro text',
            'content' => 'Body content',
            'category_id' => 1,
            'type' => PostType::Article->value,
            'is_featured' => 0,
            'status' => PostStatus::Published->value,
            'published_at' => now()->toDateTimeString(),
            'image' => UploadedFile::fake()->image('cover.jpg'),
        ]);

        $response->assertRedirect('admin/posts');

        $post = Post::where('name', 'Relative image path post')->firstOrFail();

        $this->assertNotEmpty($post->image);
        $this->assertStringNotContainsString('://', $post->image);
        $this->assertSame(parse_url($post->image, PHP_URL_PATH), $post->image);
    }
}
