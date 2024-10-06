<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('map_markers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('map_id');
            $table->string('title', 40);
            $table->string('text', 300)->nullable();
            $table->unsignedInteger('x');
            $table->unsignedInteger('y');

            $table->foreign('map_id')->references('id')->on('maps')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('map_markers');
    }
};
