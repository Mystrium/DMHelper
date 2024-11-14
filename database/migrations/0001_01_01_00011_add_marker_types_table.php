<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('map_markers', function (Blueprint $table) {
            $table->unsignedTinyInteger('type')->after('text')->default(1);
        });
    }

    public function down() {
        Schema::table('map_markers', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
