<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeaveRole;

class LeaveRolesSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'manager', 'label' => 'Manager'],
            ['name' => 'rh', 'label' => 'RH'],
            ['name' => 'drh', 'label' => 'DRH'],
            ['name' => 'direction', 'label' => 'Direction'],
        ];

        foreach ($roles as $role) {
            LeaveRole::updateOrCreate(
                ['name' => $role['name']],
                ['label' => $role['label'], 'is_active' => true]
            );
        }
    }
}