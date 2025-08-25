<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostComment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'name',
        'email',
        'content',
        'status',
        'likes_count',
        'dislikes_count'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => null,
            'email' => null,
        ]);
    }


    public function replies()
    {
        return $this->hasMany(PostComment::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(PostComment::class, 'parent_id');
    }

    public function reactions()
    {
        return $this->hasMany(CommentReaction::class, 'comment_id');
    }

    public function like(User $user)
    {
        $this->reactions()->updateOrCreate(
            ['user_id' => $user->id],
            ['reaction' => 'like']
        );
        $this->refreshCounts();
    }

    public function dislike(User $user)
    {
        $this->reactions()->updateOrCreate(
            ['user_id' => $user->id],
            ['reaction' => 'dislike']
        );
        $this->refreshCounts();
    }

    public function removeReaction(User $user)
    {
        $this->reactions()->where('user_id', $user->id)->delete();
        $this->refreshCounts();
    }

    public function hasReactionFrom(User $user)
    {
        return $this->reactions()->where('user_id', $user->id)->exists();
    }

    public function getUserReaction(User $user)
    {
        return $this->reactions()->where('user_id', $user->id)->value('reaction');
    }

    protected function refreshCounts()
    {
        $this->update([
            'likes_count' => $this->reactions()->where('reaction', 'like')->count(),
            'dislikes_count' => $this->reactions()->where('reaction', 'dislike')->count()
        ]);
    }
    
}
