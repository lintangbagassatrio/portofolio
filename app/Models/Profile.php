<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'birth_place_date',
        'education',
        'address',
        'email',
        'phone',
        'bio',
        'photo',
        'cv_file',
        'background',
        'career',
        'interests',
        'goals',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
