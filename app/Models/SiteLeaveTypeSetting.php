<?php
// app/Models/SiteLeaveTypeSetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteLeaveTypeSetting extends Model
{
    use HasFactory;

    protected $table = 'site_leave_type_settings';

    protected $fillable = [
        'site_id',
        'leave_type_id',
        'is_enabled',
        'local_name',
        'local_color',
        'local_requires_attachment',
        'local_requires_attachment_after',
        'local_allow_negative_balance',
        'local_max_negative_limit',
        'local_deducts_balance',
        'local_min_notice_days',
        'local_max_duration_per_request',
        'local_allow_overlap',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'local_allow_negative_balance' => 'boolean',
        'local_deducts_balance' => 'boolean',
        'local_requires_attachment_after' => 'integer',
        'local_max_negative_limit' => 'integer',
        'local_min_notice_days' => 'integer',
        'local_max_duration_per_request' => 'decimal:2',
        'local_allow_overlap' => 'boolean',
    ];

    public function site()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'site_id', 'ID');
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    // Accessors pour récupérer la valeur ou hériter du global
    public function getName($globalName)
    {
        return $this->local_name ?? $globalName;
    }

    public function getColor($globalColor)
    {
        return $this->local_color ?? $globalColor;
    }

    public function getRequiresAttachment($globalValue)
    {
        return $this->local_requires_attachment ?? $globalValue;
    }

    public function getRequiresAttachmentAfter($globalValue)
    {
        return $this->local_requires_attachment_after ?? $globalValue;
    }

    public function getAllowNegativeBalance($globalValue)
    {
        return $this->local_allow_negative_balance ?? $globalValue;
    }

    public function getMaxNegativeLimit($globalValue)
    {
        return $this->local_max_negative_limit ?? $globalValue;
    }

    public function getDeductsBalance($globalValue)
    {
        return $this->local_deducts_balance ?? $globalValue;
    }

    public function getMinNoticeDays($globalValue)
    {
        return $this->local_min_notice_days ?? $globalValue;
    }

    public function getMaxDurationPerRequest($globalValue)
    {
        return $this->local_max_duration_per_request ?? $globalValue;
    }

    public function getAllowOverlap($globalValue)
    {
        return $this->local_allow_overlap ?? $globalValue;
    }
}