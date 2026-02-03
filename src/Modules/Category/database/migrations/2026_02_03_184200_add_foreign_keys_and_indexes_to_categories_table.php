<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Add foreign key constraints and indexes to categories table.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
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
            $table->unique('slug');
            $table->index('status');
            $table->index('created_by');
            $table->index('updated_by');
            $table->index('deleted_by');
            $table->index(['status', 'created_at'], 'categories_status_created_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Drop foreign keys
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['deleted_by']);

            // Drop indexes
            $table->dropIndex('categories_status_created_index');
            $table->dropUnique(['slug']);
            $table->dropIndex(['status']);
            $table->dropIndex(['created_by']);
            $table->dropIndex(['updated_by']);
            $table->dropIndex(['deleted_by']);
        });
    }
};
