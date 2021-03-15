<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('posted_by')->constrained('users', 'id')->cascadeOnDelete();
            $table->string('category')->index();
            $table->string('post_type');
            $table->string('title')->unique();
            $table->string('slug')->unique()->index();
            $table->longText('content')->nullable();
            $table->string('image')->nullable(); // temporary
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
