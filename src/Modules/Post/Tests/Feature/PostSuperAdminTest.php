<?php

namespace Nasirkhan\ModuleManager\Modules\Post\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Nasirkhan\ModuleManager\Modules\Post\Models\Post;
use Tests\TestCase;

class PostSuperAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // seed the database
        $this->seed();

        // Insert demo data for posts
        Artisan::call('laravel-starter:insert-demo-data');

        // Get Super Admin
        $user = User::whereId(1)->first();

        $this->actingAs($user);
    }

    /**
     * Posts Test.
     *
     * ---------------------------------------------------------------
     */
    public function test_super_admin_user_can_view_posts_index(): void
    {
        $response = $this->get('/admin/posts');

        $response->assertStatus(200);
    }

    public function test_super_admin_user_can_create_post(): void
    {
        $response = $this->get('/admin/posts/create');

        $response->assertStatus(200);
    }

    public function test_super_admin_user_can_show_post(): void
    {
        $response = $this->get('/admin/posts/1');

        $response->assertStatus(200);
    }

    public function test_super_admin_user_can_edit_post(): void
    {
        $response = $this->get('/admin/posts/1/edit');

        $response->assertStatus(200);
    }

    public function test_super_admin_user_can_delete_post(): void
    {
        $model_id = 5;

        $model = Post::find($model_id);

        $this->assertModelExists($model);

        $model->delete();

        $this->assertSoftDeleted($model);
    }

    public function test_super_admin_user_can_view_trashed_post(): void
    {
        $model_id = 5;

        $model = Post::find($model_id);

        $this->assertModelExists($model);

        $model->delete();

        $this->assertDatabaseMissing('posts', [
            'id' => $model_id,
            'deleted_at' => null,
        ]);
    }

    public function test_super_admin_user_can_restore_trashed_post(): void
    {
        $model_id = 5;

        $response = $this->delete('/admin/posts/'.$model_id);

        $response->assertStatus(302);

        $response->assertRedirect('/admin/posts');

        $model = Post::withTrashed()->find($model_id)->first();

        $model->restore();

        $this->assertModelExists($model);
    }

    public function test_super_admin_user_can_restore_post(): void
    {
        $model_id = 5;

        $model = Post::find($model_id);

        $this->assertModelExists($model);

        $model->delete();

        $this->assertSoftDeleted($model);
    }
}
