<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('name'); // e.g., "Header Menu", "Footer Menu", "Admin Sidebar"
            $table->string('slug')->unique(); // e.g., "header-menu", "footer-menu"
            $table->text('description')->nullable(); // Description of the menu purpose

            // Location & Display
            $table->string('location'); // e.g., "header", "footer", "sidebar", "mobile"
            $table->string('theme')->default('default'); // Theme variant (if multiple themes)
            $table->text('css_classes')->nullable(); // CSS classes for the menu container
            $table->json('settings')->nullable(); // Additional menu settings (max_depth, etc.)

            // Access Control (for the entire menu group)
            $table->json('permissions')->nullable(); // Required permissions to see this menu
            $table->json('roles')->nullable(); // Required roles to see this menu
            $table->boolean('is_public')->default(true); // Can guests see this menu?

            // Status & Visibility
            $table->boolean('is_active')->default(true);
            $table->boolean('is_visible')->default(true);

            // Multi-language Support
            $table->string('locale')->nullable();

            // Metadata
            $table->text('note')->nullable(); // Admin notes
            $table->tinyInteger('status')->default(1); // 0=disabled, 1=enabled, 2=draft

            // Audit Fields
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

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
            $table->index('location'); // Filter by location (header, footer, etc.)
            $table->index('status');
            $table->index('is_active');
            $table->index('created_by');
            $table->index('updated_by');
            $table->index('deleted_by');
            $table->index(['locale']);
            $table->index(['is_public', 'is_visible']);

            // Composite index for common query (location + active)
            $table->index(['location', 'is_active'], 'menus_location_active_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
};
