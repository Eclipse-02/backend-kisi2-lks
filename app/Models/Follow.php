<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Follow extends Model
{
    use HasFactory;

    protected $fillable = [
        'follower_id',
        'following_id',
        'is_accepted',
    ];

    public function userFollowing() : HasMany {
        return $this->hasMany(User::class, 'id', 'following_id');
    }

    public function userFollower() : HasMany {
        return $this->hasMany(User::class, 'id', 'follower_id');
    }
}
