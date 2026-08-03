<?php
// app/Models/CalculationRule.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalculationRule extends Model
{
    protected $fillable = [
        'leave_type_id', 'name', 'formula',
        'output_variable', 'is_active', 'sort_order'
    ];
}