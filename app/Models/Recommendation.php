<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_category',
        'motor_type',
        'fuel_system',
        'title',
        'content',
        'image'
    ];
}
