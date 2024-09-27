<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Music extends Model {

    public $table="musics";
    protected $fillable = [
        'music_category_id', 
        'music_list_id', 
        'youtube_url'
    ];

    public function category(): BelongsTo {
        return $this->belongsTo(MusicCategory::class, 'music_category_id');
    }

    public function musicList(): BelongsTo {
        return $this->belongsTo(MusicList::class, 'music_list_id');
    }

    public $timestamps = false;
}
