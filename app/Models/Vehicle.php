<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_category',
        'motor_name',
        'type',
        'fuel_system',
        'current_km',
        'stnk_tax_due_date',
        'five_year_tax_due_date',
        'iot_token'
    ];

    protected $casts = [
        'stnk_tax_due_date' => 'date',
        'five_year_tax_due_date' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($vehicle) {
            if (empty($vehicle->iot_token)) {
                $vehicle->iot_token = 'OTO-IOT-' . strtoupper(\Illuminate\Support\Str::random(8));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function history()
    {
        return $this->hasMany(ServiceHistory::class);
    }
}
