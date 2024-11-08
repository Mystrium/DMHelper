<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'title', 
        'setting',
        'visible',
        'music_list_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function musicList() {
        return $this->belongsTo(MusicList::class);
    }

    public function story() {
        return $this->hasMany(Story::class);
    }

    public function map() {
        return $this->hasMany(Map::class);
    }

    public function characters() {
        return $this->hasMany(Character::class);
    }

    public $timestamps = false;
}
