<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapMarker extends Model {
    use HasFactory;

    protected $fillable = [
        'map_id', 
        'title', 
        'text',
        'type',
        'x',
        'y'
    ];

    public function map(){
        return $this->belongsTo(Map::class);
    }

    public $timestamps = false;
}
