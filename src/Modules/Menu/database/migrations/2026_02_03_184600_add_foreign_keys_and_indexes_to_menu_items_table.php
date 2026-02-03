<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Add foreign key constraints and indexes to menu_items table.
     */
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            // Foreign key for menu relationship
            $table->foreign('menu_id')
                  ->references('id')
                  ->on('menus')
                  ->cascadeOnDelete(); // Delete items when menu is deleted

            // Foreign key for parent (self-referencing for nested menus)
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('menu_items')
                  ->cascadeOnDelete(); // Delete children when parent is deleted

            // Foreign keys for audit trail
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

            // Indexes
            $table->index('menu_id');
            $table->index('parent_id');
            $table->index('order');
            $table->index('is_active');
            $table->index('status');
            $table->index('created_by');
            $table->index('updated_by');
            $table->index('deleted_by');

            // Composite indexes for common queries
            $table->index(['menu_id', 'parent_id', 'order'], 'menu_items_hierarchy_index');
            $table->index(['menu_id', 'is_active'], 'menu_items_active_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            // Drop foreign keys
            $table->dropForeign(['menu_id']);
            $table->dropForeign(['parent_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['deleted_by']);

            // Drop indexes
            $table->dropIndex('menu_items_hierarchy_index');
            $table->dropIndex('menu_items_active_index');
            $table->dropIndex(['menu_id']);
            $table->dropIndex(['parent_id']);
            $table->dropIndex(['order']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['status']);
            $table->dropIndex(['created_by']);
            $table->dropIndex(['updated_by']);
            $table->dropIndex(['deleted_by']);
        });
    }
};
