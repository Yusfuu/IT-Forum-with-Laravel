<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'uid',
        'title',
        'question',
        'tags'
    ];


    public function ownedBy(User $user)
    {
        return $user->id === $this->uid;
    }

    public function likes()
    {
        return $this->hasMany(Likes::class, 'qid');
    }


    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function likedBy(User $user)
    {
        return $this->likes->contains('uid', $user->id);
    }
}
