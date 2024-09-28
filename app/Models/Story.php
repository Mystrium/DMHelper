<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model {
    use HasFactory;

    protected $fillable = [
        'title', 
        'text', 
        'coordinates', 
        'completed',
        'game_id'
    ];

    public function linkedFrom() {
        return $this->belongsToMany(Story::class, 'story_links', 'story_to_id', 'story_from_id');
    }

    public function linkedTo() {
        return $this->belongsToMany(Story::class, 'story_links', 'story_from_id', 'story_to_id');
    }
    public $timestamps = false;
}
