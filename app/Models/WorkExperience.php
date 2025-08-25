<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkExperience extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['user_id','name','job','from','to','description'];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
