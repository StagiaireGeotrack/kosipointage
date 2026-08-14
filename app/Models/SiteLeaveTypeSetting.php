<?php

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
    ];

    protected $casts = [
        'is_enabled'                     => 'boolean',
        'local_allow_negative_balance'   => 'boolean',
        'local_deducts_balance'          => 'boolean',
        'local_requires_attachment_after'=> 'integer',
        'local_max_negative_limit'       => 'integer',
    ];

    public function site()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'site_id', 'ID');
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }
}