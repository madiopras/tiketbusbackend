<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use DB; // Tambahkan ini untuk menggunakan DB

class Buses extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'bus_number',
        'capacity',
        'operator_name',
        'class_id',
        'is_active',
        'created_by_id',
        'updated_by_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Fungsi scope untuk memfilter data
    public function scopeFilterWithJoin($query, $filters)
    {
        $query->select('buses.id', 'buses.bus_number', 'classes.class_name', 'buses.capacity', 'buses.operator_name')
              ->join('classes', 'buses.class_id', '=', 'classes.id')
              ->orderBy('buses.bus_number', 'asc');

        if (isset($filters['bus_number'])) {
            $query->where('buses.bus_number', 'like', '%' . $filters['bus_number'] . '%');
        }
        if (isset($filters['operator_name'])) {
            $query->where('buses.operator_name', 'like', '%' . $filters['operator_name'] . '%');
        }
        if (isset($filters['class_name'])) {
            $query->where('classes.class_name', 'like', '%' . $filters['class_name'] . '%');
        }
        if (isset($filters['is_active'])) {
            $query->where('buses.is_active', $filters['is_active']);
        }
        
        return $query;
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
}