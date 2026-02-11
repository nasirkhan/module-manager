<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('taggables', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tag_id')->unsigned();
            $table->bigInteger('taggable_id')->unsigned();
            $table->string('taggable_type');

            // Foreign key for tag relationship
            $table->foreign('tag_id')
                ->references('id')
                ->on('tags')
                ->cascadeOnDelete(); // Delete pivot when tag is deleted

            // Indexes for polymorphic relationship
            $table->index('tag_id');
            $table->index(['taggable_id', 'taggable_type'], 'taggables_taggable_index');

            // Unique constraint to prevent duplicate tags on same entity
            $table->unique(['tag_id', 'taggable_id', 'taggable_type'], 'taggables_unique_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taggables');
    }
};
