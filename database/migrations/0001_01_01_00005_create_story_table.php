<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50);
            $table->string('text', 300);
            $table->json('coordinates')->nullable();
            $table->boolean('completed')->default(false);
            
            $table->unsignedBigInteger('game_id');
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('stories');
    }
};