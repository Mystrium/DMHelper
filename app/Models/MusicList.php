<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MusicList extends Model {
    protected $fillable = [
        'title', 
        'description',
        'visible',
        'user_id'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function music(): HasMany {
        return $this->hasMany(Music::class);
    }

    public $timestamps = false;
}
