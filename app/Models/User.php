<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use SoftDeletes;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'job_title',
        'birthdate',
        'address',
        'phone',
        'bio',
        'role',
        'account_state'
    ];

    public function SocialNetwork()
    {
        return $this->hasMany(SocialNetwork::class);
    }

    public function Skill()
    {
        return $this->hasMany(Skill::class);
    }

    public function Category()
    {
        return $this->hasMany(Category::class);
    }

    public function Tag()
    {
        return $this->hasMany(Tag::class);
    }

    public function WorkExperience()
    {
        return $this->hasMany(WorkExperience::class);
    }

    public function Education()
    {
        return $this->hasMany(Education::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getImagenAttribute()
    {   
        
        if ($this->image != null)
            return file_exists('storage/users/profile/' . $this->image) ? 'users/profile/' . $this->image : 'noimguser.png';
        else
            return 'noimguser.png';

        
    }

    
    
}
