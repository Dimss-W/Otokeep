<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceHistory extends Model
{
    use HasFactory;

    protected $table = 'service_history';

    protected $fillable = [
        'vehicle_id',
        'category_id',
        'custom_service_name',
        'service_date',
        'service_km',
        'cost',
        'workshop_name',
        'notes',
    ];

    protected $casts = [
        'service_date' => 'datetime',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    /**
     * Get display name of the service (either from category or custom input).
     */
    public function getServiceNameAttribute(): string
    {
        if ($this->category) {
            return $this->category->name;
        }

        return $this->custom_service_name ?: 'Servis Berkala';
    }

    /**
     * Check if this was a custom outside-system service.
     */
    public function isCustom(): bool
    {
        return empty($this->category_id);
    }
}
