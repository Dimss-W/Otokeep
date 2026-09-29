<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'notification_time',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function vehicle()
    {
        // Tetap mendukung $user->vehicle secara backward compatible
        return $this->hasOne(Vehicle::class)->oldestOfMany();
    }

    public function getActiveVehicleAttribute()
    {
        $activeId = session('active_vehicle_id');
        if ($activeId) {
            $vehicle = $this->vehicles()->find($activeId);
            if ($vehicle) return $vehicle;
        }
        return $this->vehicles()->first();
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at', 'asc');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
