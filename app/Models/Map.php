<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Map extends Model {
    use HasFactory;

    protected $fillable = [
        'game_id',
        'title',
        'file_name'
    ];

    public function markers() {
        return $this->hasMany(MapMarker::class);
    }

    public $timestamps = false;
}
