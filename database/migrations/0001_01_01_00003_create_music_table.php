<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('musics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('music_category_id');
            $table->unsignedBigInteger('music_list_id');
            $table->string('youtube_url', 50);

            $table->foreign('music_category_id')->references('id')->on('music_categories')->onDelete('cascade');
            $table->foreign('music_list_id')->references('id')->on('music_lists')->onDelete('cascade');
        });
    }
    
    public function down(){
        Schema::dropIfExists('music');
    }
};