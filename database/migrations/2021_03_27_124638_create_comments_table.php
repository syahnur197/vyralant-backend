<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // when a user delete a comment, we just delete the content and the posted by
        Schema::create('comments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('post_id')->constrained('posts', 'id')->cascadeOnDelete();

            $table->foreignUuid('posted_by')
                ->nullable()
                ->constrained('users', 'id')
                ->cascadeOnDelete();


            $table->longText('content')
                ->nullable();

            $table->timestamps();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->foreignUuid('parent_id')
                ->after('content')
                ->nullable()
                ->constrained('comments', 'id')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
