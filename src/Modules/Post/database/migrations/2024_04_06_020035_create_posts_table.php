<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Nasirkhan\ModuleManager\Modules\Post\Enums\PostStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('intro')->nullable();
            $table->longText('content')->nullable();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('category_name')->nullable();
            $table->integer('is_featured')->nullable();
            $table->string('image')->nullable();

            $table->string('meta_title')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_og_image')->nullable();
            $table->string('meta_og_url')->nullable();

            $table->integer('hits')->default(0)->unsigned();
            $table->integer('order')->nullable();
            $table->string('status')->default(PostStatus::Published->name);

            $table->unsignedBigInteger('moderated_by')->nullable();
            $table->datetime('moderated_at')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('created_by_name')->nullable();
            $table->string('created_by_alias')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign key for category relationship
            // Use nullOnDelete() so posts aren't deleted when category is deleted
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->nullOnDelete();

            // Foreign keys for audit trail - use noActionOnDelete()
            // to preserve user ID even if user is deleted
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->noActionOnDelete();

            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->noActionOnDelete();

            $table->foreign('deleted_by')
                ->references('id')
                ->on('users')
                ->noActionOnDelete();

            $table->foreign('moderated_by')
                ->references('id')
                ->on('users')
                ->noActionOnDelete();

            // Unique index for slug (SEO URLs)
            $table->unique('slug');

            // Indexes for foreign keys (improves join performance)
            $table->index('category_id');
            $table->index('created_by');
            $table->index('updated_by');
            $table->index('deleted_by');
            $table->index('moderated_by');

            // Indexes for frequently queried columns
            $table->index('status'); // Filter by published/draft
            $table->index('published_at'); // Order by publish date
            $table->index('is_featured'); // Filter featured posts
            $table->index('type'); // Filter by post type

            // Composite index for common queries (status + published_at)
            $table->index(['status', 'published_at'], 'posts_status_published_index');
        });

        // Full-text index for search (MySQL/MariaDB only)
        // Other databases (SQLite, PostgreSQL, SQL Server) will skip this
        $driver = Schema::getConnection()->getDriverName();
        if (in_array($driver, ['mysql', 'mariadb'])) {
            try {
                DB::statement('ALTER TABLE posts ADD FULLTEXT search_index (name, intro, content)');
            } catch (\Exception $e) {
                // Silently skip if fulltext is not supported
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop full-text index if it exists (MySQL/MariaDB only)
        $driver = Schema::getConnection()->getDriverName();
        if (in_array($driver, ['mysql', 'mariadb'])) {
            try {
                DB::statement('ALTER TABLE posts DROP INDEX IF EXISTS search_index');
            } catch (\Exception $e) {
                // Silently skip if index doesn't exist
            }
        }

        Schema::dropIfExists('posts');
    }
};
