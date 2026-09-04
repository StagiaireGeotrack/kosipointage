<?php
// app/Models/Employe.php

namespace App\Models;

use App\Scopes\SiegeScope;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Loggable;

class Employe extends Authenticatable
{
    use Loggable;
    public string $logLabelField = 'Nom';

    protected $table = 'Employes';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    
    protected $fillable = [
        'Nom',
        'BadgeID',
        'num_mat',
        'HasBiometricSetup',
        'HasFaceSetup',
        'FaceEncodingPath',
        'Pin',
        'CreatedAt',
        'deleted',
        'Actived',
        'SiegeID',
        'email',
        'telephone',
        'password',
        'remember_token',
        // ============ ORGANISATION ============
        'company_id',
        'site_id',
        'department_id',
        'job_title_id',
        'hierarchy_level_id',
        'manager_id',
        'employment_status',
        'hire_date',
        'user_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'HasBiometricSetup' => 'boolean',
        'HasFaceSetup' => 'boolean',
        'CreatedAt' => 'datetime',
        'Actived' => 'boolean',
        'hire_date' => 'date',
        'deleted' => 'boolean',
    ];
    
    // ============ RELATIONS ============
    public function siege(): BelongsTo
    {
        return $this->belongsTo(EntrepriseSiege::class, 'SiegeID', 'ID');
    }
    
    public function pointages(): HasMany
    {
        return $this->hasMany(Pointage::class, 'employee_id', 'ID');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function jobTitle()
    {
        return $this->belongsTo(JobTitle::class, 'job_title_id');
    }

    public function hierarchyLevel()
    {
        return $this->belongsTo(HierarchyLevel::class, 'hierarchy_level_id');
    }

    public function manager()
    {
        return $this->belongsTo(Employe::class, 'manager_id');
    }

    public function subordinates()
    {
        return $this->hasMany(Employe::class, 'manager_id');
    }

    public function site()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'SiegeID');
    }

    // ==============================================
    // RELATIONS POUR LES CONGÉS (AJOUTÉES)
    // ==============================================
    
    /**
     * Relation avec les demandes de congé (nouveau système LeaveRequest)
     */
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class, 'employee_id', 'ID');
    }

    /**
     * Relation avec les congés (ancien système Conge)
     */
    public function conges()
    {
        return $this->hasMany(Conge::class, 'employee_id', 'ID');
    }

    /**
     * Relation avec les soldes de congés
     */
    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class, 'employee_id', 'ID');
    }

    // ============ ACCESSORS ============
    public function getDepartmentNameAttribute()
    {
        return $this->department?->name ?? 'Non défini';
    }

    public function getJobTitleNameAttribute()
    {
        return $this->jobTitle?->name ?? 'Non défini';
    }

    public function getManagerNameAttribute()
    {
        return $this->manager?->Nom ?? 'Aucun manager';
    }

    public function getHierarchyLevelNameAttribute()
    {
        return $this->hierarchyLevel?->name ?? 'Non défini';
    }

    public function getEmploymentStatusLabelAttribute()
    {
        return match($this->employment_status) {
            'actif' => 'Actif',
            'suspendu' => 'Suspendu',
            'sorti' => 'Sorti',
            default => $this->employment_status ?? 'Actif',
        };
    }

    public function getFullInfoAttribute()
    {
        return $this->Nom . ' (' . ($this->num_mat ?? 'N/A') . ')';
    }

    public function getHireDateFormattedAttribute()
    {
        return $this->hire_date ? \Carbon\Carbon::parse($this->hire_date)->format('d/m/Y') : '-';
    }

    // ============ SCOPES ============
    public function scopeActive($query)
    {
        return $query->where('Actived', 1)->where('deleted', 0);
    }

    public function scopeBySite($query, $siteId)
    {
        return $query->where('SiegeID', $siteId);
    }

    // ============ BOOT ============
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SiegeScope());
    }
}