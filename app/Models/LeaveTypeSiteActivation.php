<?php
// app/Models/LeaveTypeSiteActivation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveTypeSiteActivation extends Model
{
    use HasFactory;

    protected $table = 'leave_type_site_activations';

    protected $fillable = [
        'site_id',
        'leave_type_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    public function site()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'site_id', 'ID');
    }
}