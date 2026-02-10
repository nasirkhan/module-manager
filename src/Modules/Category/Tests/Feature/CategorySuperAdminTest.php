<?php

namespace Nasirkhan\ModuleManager\Modules\Category\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Nasirkhan\ModuleManager\Modules\Category\Models\Category;
use Tests\TestCase;

class CategorySuperAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // seed the database
        $this->seed();

        // Insert demo data for categories
        Artisan::call('laravel-starter:insert-demo-data');

        // Get Super Admin
        $user = User::whereId(1)->first();

        $this->actingAs($user);
    }

    /**
     * Categories Test.
     *
     * ---------------------------------------------------------------
     */
    public function test_super_admin_user_can_view_categories_index(): void
    {
        $response = $this->get('/admin/categories');

        $response->assertStatus(200);
    }

    public function test_super_admin_user_can_create_category(): void
    {
        $response = $this->get('/admin/categories/create');

        $response->assertStatus(200);
    }

    public function test_super_admin_user_can_show_category(): void
    {
        $response = $this->get('/admin/categories/1');

        $response->assertStatus(200);
    }

    public function test_super_admin_user_can_edit_category(): void
    {
        $response = $this->get('/admin/categories/1/edit');

        $response->assertStatus(200);
    }

    public function test_super_admin_user_can_delete_category(): void
    {
        $model_id = 5;

        $model = Category::find($model_id);

        $this->assertModelExists($model);

        $model->delete();

        $this->assertSoftDeleted($model);
    }

    public function test_super_admin_user_can_view_trashed_category(): void
    {
        $model_id = 5;

        $model = Category::find($model_id);

        $this->assertModelExists($model);

        $model->delete();

        $this->assertDatabaseMissing('categories', [
            'id' => $model_id,
            'deleted_at' => null,
        ]);
    }

    public function test_super_admin_user_can_restore_trashed_category(): void
    {
        $model_id = 5;

        $response = $this->delete('/admin/categories/'.$model_id);

        $response->assertStatus(302);

        $response->assertRedirect('/admin/categories');

        $model = Category::withTrashed()->find($model_id)->first();

        $model->restore();

        $this->assertModelExists($model);
    }

    public function test_super_admin_user_can_restore_category(): void
    {
        $model_id = 5;

        $model = Category::find($model_id);

        $this->assertModelExists($model);

        $model->delete();

        $this->assertSoftDeleted($model);
    }
}
