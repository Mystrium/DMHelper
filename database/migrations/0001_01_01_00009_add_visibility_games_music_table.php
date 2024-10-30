<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('games', function (Blueprint $table) {
            $table->boolean('visible')->default(true)->after('setting')->default(1);
        });

        Schema::table('music_lists', function (Blueprint $table) {
            $table->boolean('visible')->default(true)->after('title')->default(1);
        });
    }

    public function down() {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('visible');
        });

        Schema::table('music_lists', function (Blueprint $table) {
            $table->dropColumn('visible');
        });
    }
};
