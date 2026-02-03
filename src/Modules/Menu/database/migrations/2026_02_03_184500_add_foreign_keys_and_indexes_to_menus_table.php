<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Add foreign key constraints and indexes to menus table.
     */
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
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

            // Indexes (slug is already unique in create migration)
            $table->index('location'); // Filter by location (header, footer, etc.)
            $table->index('status');
            $table->index('is_active');
            $table->index('created_by');
            $table->index('updated_by');
            $table->index('deleted_by');

            // Composite index for common query (location + active)
            $table->index(['location', 'is_active'], 'menus_location_active_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            // Drop foreign keys
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['deleted_by']);

            // Drop indexes
            $table->dropIndex('menus_location_active_index');
            $table->dropIndex(['location']);
            $table->dropIndex(['status']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['created_by']);
            $table->dropIndex(['updated_by']);
            $table->dropIndex(['deleted_by']);
        });
    }
};
