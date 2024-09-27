<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MusicCategory extends Model {
    protected $fillable = ['title'];

    public function music(): HasMany {
        return $this->hasMany(Music::class);
    }
    
    public $timestamps = false;
}
