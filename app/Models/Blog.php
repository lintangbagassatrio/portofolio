<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'slug',
        'thumbnail',
        'content',
        'tags',
        'status', // 'draft' or 'published'
        'views',
    ];

    protected $casts = [
        'tags' => 'array',
        'views' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class);
    }

    public function approvedComments()
    {
        return $this->hasMany(BlogComment::class)->where('is_approved', true);
    }
}
