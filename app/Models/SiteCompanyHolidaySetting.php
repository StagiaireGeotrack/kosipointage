<?php
// app/Models/SiteCompanyHolidaySetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteCompanyHolidaySetting extends Model
{
    use HasFactory;

    protected $table = 'site_company_holiday_settings';

    protected $fillable = [
        'site_id',
        'company_holiday_id',
        'date',
        'name',
        'is_recurring',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'is_recurring' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function site()
    {
        return $this->belongsTo(EntrepriseSiege::class, 'site_id', 'ID');
    }

    public function companyHoliday()
    {
        return $this->belongsTo(CompanyHoliday::class, 'company_holiday_id');
    }
}