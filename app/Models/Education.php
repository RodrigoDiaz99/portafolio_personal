<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Education extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['user_id','institution','study_level','title_obtained','date','description'];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
