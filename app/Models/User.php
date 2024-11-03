<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    use HasFactory;
    protected $fillable = [
        'email',
        'name',
        'password',
        'is_admin',
        'banned'
    ];

    protected $hidden = [ 'password' ];

    protected function casts(): array {
        return [ 'password' => 'hashed' ];
    }

    public $timestamps = false;
}
