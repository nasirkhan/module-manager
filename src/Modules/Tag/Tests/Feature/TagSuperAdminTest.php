<?php

namespace Nasirkhan\ModuleManager\Modules\Tag\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Nasirkhan\ModuleManager\Modules\Tag\Models\Tag;
use Tests\TestCase;

class TagSuperAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // seed the database
        $this->seed();

        // Insert demo data for tags
        Artisan::call('laravel-starter:insert-demo-data');

        // Get Super Admin
        $user = User::whereId(1)->first();

        $this->actingAs($user);
    }

    /**
     * Tags Test.
     *
     * ---------------------------------------------------------------
     */
    public function test_super_admin_user_can_view_tags_index(): void
    {
        $response = $this->get('/admin/tags');

        $response->assertStatus(200);
    }

    public function test_super_admin_user_can_create_tag(): void
    {
        $response = $this->get('/admin/tags/create');

        $response->assertStatus(200);
    }

    public function test_super_admin_user_can_show_tag(): void
    {
        $response = $this->get('/admin/tags/1');

        $response->assertStatus(200);
    }

    public function test_super_admin_user_can_edit_tag(): void
    {
        $response = $this->get('/admin/tags/1/edit');

        $response->assertStatus(200);
    }

    public function test_super_admin_user_can_delete_tag(): void
    {
        $model_id = 5;

        $model = Tag::find($model_id);

        $this->assertModelExists($model);

        $model->delete();

        $this->assertSoftDeleted($model);
    }

    public function test_super_admin_user_can_view_trashed_tag(): void
    {
        $model_id = 5;

        $model = Tag::find($model_id);

        $this->assertModelExists($model);

        $model->delete();

        $this->assertDatabaseMissing('tags', [
            'id' => $model_id,
            'deleted_at' => null,
        ]);
    }

    public function test_super_admin_user_can_restore_trashed_tag(): void
    {
        $model_id = 5;

        $response = $this->delete('/admin/tags/'.$model_id);

        $response->assertStatus(302);

        $response->assertRedirect('/admin/tags');

        $model = Tag::withTrashed()->find($model_id)->first();

        $model->restore();

        $this->assertModelExists($model);
    }

    public function test_super_admin_user_can_restore_tag(): void
    {
        $model_id = 5;

        $model = Tag::find($model_id);

        $this->assertModelExists($model);

        $model->delete();

        $this->assertSoftDeleted($model);
    }
}
