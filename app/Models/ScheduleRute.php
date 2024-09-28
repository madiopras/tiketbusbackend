<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class ScheduleRute extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'schedule_routes';

    protected $fillable = [
        'schedule_id',
        'route_id',
        'sequence_route',
        'departure_time',
        'arrival_time',
        'price_rute',
        'description'
    ];

     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

     protected $casts = [
        'sequence_route' => 'integer',
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
        'price_rute' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope a query to filter schedules based on given filters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */

     public function scopeFilter($query, $filters)
    {
        if (isset($filters['description'])) {
            $query->where('description', $filters['description']);
        }
    }

    public function schedule()
    {
        return $this->belongsTo(Schedules::class, 'schedule_id');
    }

    public function route()
    {
        return $this->belongsTo(Routes::class, 'route_id');
    }

    /**
     * Get the user who created this schedule.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * Get the user who updated this schedule.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }
}
