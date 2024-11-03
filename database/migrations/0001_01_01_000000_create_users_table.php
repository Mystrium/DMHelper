<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email', 50)->unique();
            $table->string('name', 30);
            $table->string('password', 150);
            $table->boolean('is_admin')->default(0);
            $table->boolean('banned')->default(0);
        });
    }

    public function down() {
        Schema::dropIfExists('users');
    }
};
