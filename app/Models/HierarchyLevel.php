<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HierarchyLevel extends Model
{
    use HasFactory;

    protected $table = 'hierarchy_levels';
    protected $fillable = ['company_id', 'name', 'rank', 'is_managerial'];

    public function employes()
    {
        return $this->hasMany(Employe::class, 'hierarchy_level_id');
    }
}