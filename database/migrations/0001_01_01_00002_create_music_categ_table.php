<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){
        Schema::create('music_categories', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50);
        });
    }

    public function down(){
        Schema::dropIfExists('music_categories');
    }
};