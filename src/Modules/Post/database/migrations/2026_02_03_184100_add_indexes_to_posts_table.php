<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Add indexes to posts table for improved query performance.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
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

            // Full-text index for search (if using MySQL)
            if (Schema::connection($this->getConnection())->getConnection()->getDriverName() === 'mysql') {
                DB::statement('ALTER TABLE posts ADD FULLTEXT search_index (name, intro, content)');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Drop full-text index first (MySQL)
            if (Schema::connection($this->getConnection())->getConnection()->getDriverName() === 'mysql') {
                DB::statement('ALTER TABLE posts DROP INDEX search_index');
            }

            // Drop composite indexes
            $table->dropIndex('posts_status_published_index');

            // Drop single column indexes
            $table->dropUnique(['slug']);
            $table->dropIndex(['category_id']);
            $table->dropIndex(['created_by']);
            $table->dropIndex(['updated_by']);
            $table->dropIndex(['deleted_by']);
            $table->dropIndex(['moderated_by']);
            $table->dropIndex(['status']);
            $table->dropIndex(['published_at']);
            $table->dropIndex(['is_featured']);
            $table->dropIndex(['type']);
        });
    }
};
