<?php
// app/Models/HierarchyLevel.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HierarchyLevel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hierarchy_levels';
    
    protected $fillable = [
        'company_id',
        'name',
        'code',
        'rank',
        'is_managerial',
    ];

    protected $casts = [
        'is_managerial' => 'boolean',
    ];

    // RELATION AVEC LES EMPLOYÉS
    public function employees()
    {
        return $this->hasMany(Employe::class, 'hierarchy_level_id');
    }

    // ... le reste du code
}