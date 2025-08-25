<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialNetwork extends Model
{
    protected $fillable = ['user_id','name','url','username'];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
