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
     * Note: menu_id and parent_id foreign keys are already defined in create_menu_items_table migration.
     */
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            // Note: menu_id and parent_id foreign keys already exist from the create migration
            // We only add the audit trail foreign keys here

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
            $table->index('sort_order');
            $table->index('is_active');
            $table->index('status');
            $table->index('created_by');
            $table->index('updated_by');
            $table->index('deleted_by');

            // Composite indexes for common queries
            $table->index(['menu_id', 'parent_id', 'sort_order'], 'menu_items_hierarchy_index');
            $table->index(['menu_id', 'is_active'], 'menu_items_active_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            // Drop foreign keys (only audit trail ones)
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['deleted_by']);

            // Drop indexes
            $table->dropIndex('menu_items_hierarchy_index');
            $table->dropIndex('menu_items_active_index');
            $table->dropIndex(['menu_id']);
            $table->dropIndex(['parent_id']);
            $table->dropIndex(['sort_order']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['status']);
            $table->dropIndex(['created_by']);
            $table->dropIndex(['updated_by']);
            $table->dropIndex(['deleted_by']);
        });
    }
};
