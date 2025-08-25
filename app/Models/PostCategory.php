<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostCategory extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['user_id','name','slug','description'];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Post()
    {
        return $this->hasMany(Post::class);
    }
}
