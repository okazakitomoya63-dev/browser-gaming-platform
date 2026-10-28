<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, SoftDeletes;

    protected $fillable = ['username', 'password', 'last_login_at', 'delete_reason'];
    protected $hidden = ['password'];

    public function games() {
        return $this->hasMany(Game::class, 'created_by');
    }

    public function scores() {
        return $this->hasMany(Score::class);
    }
}
