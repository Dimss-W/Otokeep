<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'vehicle_category',
        'default_interval_km',
        'default_interval_months',
    ];

    public function services()
    {
        return $this->hasMany(Service::class, 'category_id');
    }
}
