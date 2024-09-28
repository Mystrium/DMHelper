<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('story_links', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('story_from_id');
            $table->unsignedBigInteger('story_to_id');

            $table->foreign('story_from_id')->references('id')->on('stories')->onDelete('cascade');
            $table->foreign('story_to_id')->references('id')->on('stories')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('story_links');
    }
};
