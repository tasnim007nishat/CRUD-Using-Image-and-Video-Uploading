<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sample_Crud extends Model
{
    use HasFactory;

    protected $fillable = [
        'images',
        'videos'
    ];
}
