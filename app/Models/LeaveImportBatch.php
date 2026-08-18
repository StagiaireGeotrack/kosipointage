<?php
// app/Models/LeaveImportBatch.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveImportBatch extends Model
{
    use HasFactory;

    protected $table = 'leave_import_batches';

    protected $fillable = [
        'site_id',
        'batch_number',
        'type',
        'status',
        'total_records',
        'successful_records',
        'failed_records',
        'errors',
        'file_name',
        'file_path',
        'created_by',
        'cancelled_at',
        'completed_at',
    ];

    protected $casts = [
        'errors' => 'array',
        'cancelled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // ============ RELATIONS ============
    public function site()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'site_id', 'ID');
    }

    public function creator()
    {
        return $this->belongsTo(Administration::class, 'created_by', 'ID');
    }

    // ============ ACCESSORS ============
    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'opening_balance' => 'Soldes initiaux',
            'historical_leave' => 'Congés historiques',
            'future_leave' => 'Congés futurs',
            default => $this->type,
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'En attente',
            'processing' => 'En cours',
            'completed' => 'Terminé',
            'failed' => 'Échoué',
            'cancelled' => 'Annulé',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'processing' => 'info',
            'completed' => 'success',
            'failed' => 'danger',
            'cancelled' => 'secondary',
            default => 'secondary',
        };
    }

    // ============ SCOPES ============
    public function scopeForSite($query, $siteId)
    {
        return $query->where('site_id', $siteId);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}