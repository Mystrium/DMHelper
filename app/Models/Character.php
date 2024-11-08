<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model {
    use HasFactory;

    protected $fillable = [
        'external_id',
        'game_id', 
        'name_hash',
    ];

    public function game() {
        return $this->hasOne(Game::class);
    }

    public $timestamps = false;
}
