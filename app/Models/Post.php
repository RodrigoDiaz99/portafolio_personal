<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Post extends Model
{
    use SoftDeletes;
    protected $fillable = ['user_id','title','slug','body','excerpt','thumbnails','image','post_category_id','publication_status','views_count','likes_count'];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function PostCategory()
    {
        return $this->belongsTo(PostCategory::class);
    }

    public function PostTag()
    {
        return $this->belongsToMany(PostTag::class, 'post_post_tag');
    }

    public function PostComment()
    {
        return $this->hasMany(PostComment::class);
    }


    public function getImagenAttribute()
    {   
        
        if ($this->image != null)
            return file_exists('storage/posts/' . $this->image) ? 'posts/' . $this->image : 'noimg.png';
        else
            return 'noimg.png';

    }

    public function getThumbnailpoAttribute()
    {   
        if ($this->thumbnails != null)
            return file_exists('storage/posts/thumbnails/' . $this->thumbnails) ? 'posts/thumbnails/' . $this->thumbnails : 'noimg.png';
        else
            return 'noimg.png';

    }

    public function incrementViews()
    {
        $this->views_count++;
        $this->save();
    }
}


