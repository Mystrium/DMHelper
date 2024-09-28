<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50);
            $table->string('setting', 200)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('music_list_id')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('music_list_id')->references('id')->on('music_lists')->onDelete('set null');
        });
    }

    public function down() {
        Schema::dropIfExists('games');
    }
};