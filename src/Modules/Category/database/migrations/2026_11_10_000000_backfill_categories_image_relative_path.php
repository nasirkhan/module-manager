<?php

use Illuminate\Database\Migrations\Migration;
use Nasirkhan\ModuleManager\Modules\Category\Models\Category;

return new class extends Migration
{
    /**
     * Rewrite every stored absolute image URL to a host-relative path so a
     * domain change never invalidates existing rows. Only rows whose image is
     * a non-empty absolute URL (contains '://') are touched, and updateQuietly
     * keeps timestamps and model events untouched.
     *
     * @return void
     */
    public function up()
    {
        Category::withTrashed()
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->where('image', 'like', '%://%')
            ->get()
            ->each(function (Category $category) {
                $category->updateQuietly(['image' => Category::imagePathFromUrl($category->image)]);
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No-op: the original absolute URLs cannot be reconstructed.
    }
};
