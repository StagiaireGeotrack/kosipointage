<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTitle extends Model
{
    use HasFactory;

    protected $table = 'job_titles';
    protected $fillable = ['company_id', 'name', 'code'];

    public function employes()
    {
        return $this->hasMany(Employe::class, 'job_title_id');
    }
}