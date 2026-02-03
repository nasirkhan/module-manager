<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Add foreign key constraints to posts table for referential integrity.
     * Uses noActionOnDelete() for audit fields to prevent cascading deletes.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Drop foreign keys using the Laravel naming convention
            $table->dropForeign(['category_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['deleted_by']);
            $table->dropForeign(['moderated_by']);
        });
    }
};
