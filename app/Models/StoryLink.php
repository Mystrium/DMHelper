<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoryLink extends Model {
    use HasFactory;

    protected $fillable = [
        'story_from_id', 
        'story_to_id'
    ];

    public function game() {
        return $this->belongsTo(Game::class);
    }

    public function storyFrom() {
        return $this->belongsTo(Story::class, 'story_from_id');
    }

    public function storyTo() {
        return $this->belongsTo(Story::class, 'story_to_id');
    }
    
    public $timestamps = false;
}
