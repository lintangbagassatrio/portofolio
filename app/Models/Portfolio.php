<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'thumbnail',
        'description',
        'technology_used',
        'start_date',
        'status',
        'demo_link',
        'github_link',
        'is_featured',
    ];

    protected $casts = [
        'technology_used' => 'array',
        'is_featured' => 'boolean',
        'start_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(PortfolioCategory::class, 'category_id');
    }
}
