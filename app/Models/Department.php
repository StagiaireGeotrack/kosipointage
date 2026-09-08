<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'departments';

    protected $fillable = [
        'company_id',
        'site_id',
        'name',
        'code',
        'manager_employee_id'
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    // ============ RELATIONS ============
    public function site()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'site_id');
    }

    public function managerEmployee()
    {
        return $this->belongsTo(Employe::class, 'manager_employee_id');
    }

    public function employes()
    {
        return $this->hasMany(Employe::class, 'department_id');
    }

    // NOUVELLE RELATION : les postes rattachés à ce service
    public function jobTitles()
    {
        return $this->hasMany(JobTitle::class, 'department_id');
    }

    // ============ ACCESSORS ============
    public function getEmployeeCountAttribute()
    {
        return $this->employes()->count();
    }

    public function getManagerNameAttribute()
    {
        return $this->managerEmployee?->Nom ?? 'Aucun responsable';
    }

    public function getSiteNameAttribute()
    {
        return $this->site?->Nom ?? 'Non défini';
    }

    // ============ SCOPES ============
    public function scopeBySite($query, $siteId)
    {
        return $query->where('site_id', $siteId);
    }

    public function scopeActive($query)
    {
        return $query->whereHas('employes', function ($q) {
            $q->where('Actived', 1)->where('deleted', 0);
        });
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('code', 'LIKE', "%{$search}%");
        });
    }

    // ============ BOOT ============
    protected static function boot()
    {
        parent::boot();

        // Scope multi-tenant
        static::addGlobalScope('site', function ($query) {
            $user = auth()->user();
            if ($user && !$user->IsSuperAdmin && $user->SiegeID) {
                $query->where('site_id', $user->SiegeID);
            }
        });
    }
}