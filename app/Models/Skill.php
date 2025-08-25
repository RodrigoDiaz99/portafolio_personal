<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Skill extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['user_id','category','ability','level','description','image'];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function getImagenAttribute()
    {   
        
        if ($this->image != null)
            return file_exists('storage/users/skills/' . $this->image) ? 'users/skills/' . $this->image : 'noimg.png';
        else
            return 'noimg.png';

        
    }
}
