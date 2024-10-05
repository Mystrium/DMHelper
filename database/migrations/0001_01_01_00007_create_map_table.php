<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('maps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_id');
            $table->string('title', 30);
            $table->string('file_name', 100);

            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('maps');
    }
};
