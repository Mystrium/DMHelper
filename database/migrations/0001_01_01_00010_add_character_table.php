<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('external_id')->unique();
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->string('name_hash');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('characters');
    }
};