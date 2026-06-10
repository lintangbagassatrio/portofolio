<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = [
        'ip_address',
        'user_agent',
        'visit_date',
        'page_visited',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];
}
