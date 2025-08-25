<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title',255);
            $table->string('slug',255);
            $table->text('body');
            $table->string('excerpt',255)->nullable();
            $table->string('thumbnails',255)->nullable();
            $table->string('image',255)->nullable();
            $table->foreignId('post_category_id')->constrained()->onDelete('cascade');
            $table->enum('publication_status',['Borrador','Publicado','Pendiente'])->default('Borrador');
            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedBigInteger('likes_count')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
}