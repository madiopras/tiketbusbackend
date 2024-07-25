<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Route extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'start_location_id',
        'end_location_id',
        'distance',
        'price',
        'created_by_id',
        'updated_by_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'distance' => 'float',
        'price' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope a query to filter routes based on given filters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, $filters)
    {
        if (isset($filters['start_location_id'])) {
            $query->where('start_location_id', $filters['start_location_id']);
        }
        if (isset($filters['end_location_id'])) {
            $query->where('end_location_id', $filters['end_location_id']);
        }
        if (isset($filters['distance'])) {
            $query->where('distance', $filters['distance']);
        }
        if (isset($filters['price'])) {
            $query->where('price', $filters['price']);
        }
        if (isset($filters['created_by_id'])) {
            $query->where('created_by_id', $filters['created_by_id']);
        }
        if (isset($filters['updated_by_id'])) {
            $query->where('updated_by_id', $filters['updated_by_id']);
        }
    }

    /**
     * Get the start location of the route.
     */
    public function startLocation()
    {
        return $this->belongsTo(Location::class, 'start_location_id');
    }

    /**
     * Get the end location of the route.
     */
    public function endLocation()
    {
        return $this->belongsTo(Location::class, 'end_location_id');
    }

    /**
     * Get the user who created this route.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * Get the user who updated this route.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }
}
