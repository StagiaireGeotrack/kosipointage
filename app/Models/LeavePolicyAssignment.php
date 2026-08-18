<?php
// app/Models/LeavePolicyAssignment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeavePolicyAssignment extends Model
{
    use HasFactory;

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
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
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

    // ============ SCOPES ============
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeForDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
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
        return $query->where('site_id', $siteId);
    }

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }
}