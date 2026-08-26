<?php
// app/Models/LeavePolicyAssignment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeavePolicyAssignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'leave_policy_assignments';

    protected $fillable = [
        'leave_policy_id',
        'company_id',
        'site_id',
        'department_id',
        'job_title_id',
        'hierarchy_level_id',
        'employee_id',
        'priority',
        'is_active',
        'assignment_type', // ✅ AJOUTÉ : 'individual', 'department', 'site', 'company'
        'start_date',
        'end_date',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // ============ RELATIONS ============
    
    public function leavePolicy()
    {
        return $this->belongsTo(LeavePolicy::class, 'leave_policy_id');
    }

    public function company()
    {
        return $this->belongsTo(Entreprise::class, 'company_id');
    }

    public function site()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'site_id');
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

    public function employee()
    {
        return $this->belongsTo(Employe::class, 'employee_id');
    }

    public function creator()
    {
        return $this->belongsTo(Administration::class, 'created_by');
    }

    // ============ ACCESSORS ============
    
    public function getTargetLabelAttribute()
    {
        if ($this->employee_id) {
            return 'Employé: ' . ($this->employee?->Nom ?? 'N/A');
        }
        if ($this->department_id) {
            return 'Service: ' . ($this->department?->name ?? 'N/A');
        }
        if ($this->job_title_id) {
            return 'Poste: ' . ($this->jobTitle?->name ?? 'N/A');
        }
        if ($this->hierarchy_level_id) {
            return 'Niveau: ' . ($this->hierarchyLevel?->code ?? 'N/A');
        }
        if ($this->site_id) {
            return 'Siège: ' . ($this->site?->Nom ?? 'N/A');
        }
        if ($this->company_id) {
            return 'Entreprise: ' . ($this->company?->nom ?? 'N/A');
        }
        return 'Global';
    }

    public function getPriorityLabelAttribute()
    {
        return match($this->priority) {
            100 => 'Très haute',
            75 => 'Haute',
            50 => 'Moyenne',
            25 => 'Basse',
            default => $this->priority . '%',
        };
    }

    public function getAssignmentTypeLabelAttribute()
    {
        return match($this->assignment_type) {
            'individual' => 'Individuel',
            'department' => 'Service',
            'site' => 'Siège',
            'company' => 'Entreprise',
            'global' => 'Global',
            default => $this->assignment_type,
        };
    }

    // ============ SCOPES ============
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId)
            ->where('assignment_type', 'individual');
    }

    public function scopeForDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId)
            ->where('assignment_type', 'department');
    }

    public function scopeForJobTitle($query, $jobTitleId)
    {
        return $query->where('job_title_id', $jobTitleId);
    }

    public function scopeForHierarchyLevel($query, $hierarchyLevelId)
    {
        return $query->where('hierarchy_level_id', $hierarchyLevelId);
    }

    public function scopeForSite($query, $siteId)
    {
        return $query->where('site_id', $siteId)
            ->where('assignment_type', 'site');
    }

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId)
            ->where('assignment_type', 'company');
    }

    /**
     * ✅ Scope pour récupérer les assignments valides à une date donnée
     */
    public function scopeValidAt($query, $date = null)
    {
        $date = $date ?? now();
        return $query->where(function ($q) use ($date) {
            $q->whereNull('start_date')
              ->orWhere('start_date', '<=', $date);
        })->where(function ($q) use ($date) {
            $q->whereNull('end_date')
              ->orWhere('end_date', '>=', $date);
        });
    }
}