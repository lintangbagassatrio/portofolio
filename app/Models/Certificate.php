<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'name',
        'publisher',
        'year',
        'thumbnail',
        'file_path',
        'description',
    ];
}
