<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobTitle extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'job_titles';

    protected $fillable = [
        'company_id',
        'name',
        'code',
        'hierarchy_level_id',
        'department_id',   // <-- AJOUT
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    // ============ RELATIONS ============
    public function employes()
    {
        return $this->hasMany(Employe::class, 'job_title_id');
    }

    public function hierarchyLevel()
    {
        return $this->belongsTo(HierarchyLevel::class, 'hierarchy_level_id');
    }

    // NOUVELLE RELATION : le service auquel appartient ce poste
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    // ============ ACCESSORS ============
    public function getLevelNameAttribute()
    {
        return $this->hierarchyLevel?->name ?? 'Non défini';
    }

    public function getLevelCodeAttribute()
    {
        return $this->hierarchyLevel?->code ?? '-';
    }

    public function getEmployeeCountAttribute()
    {
        return $this->employes()->count();
    }

    public function getDepartmentNameAttribute()
    {
        return $this->department?->name ?? 'Non défini';
    }

    // ============ SCOPES ============
    public function scopeByLevel($query, $levelId)
    {
        return $query->where('hierarchy_level_id', $levelId);
    }

    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('code', 'LIKE', "%{$search}%");
        });
    }
}
