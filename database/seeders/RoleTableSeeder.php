<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role_admin_user = new Role;
        $role_admin_user->abbreviation = 'admin';
        $role_admin_user->label = 'Admin';
        $role_admin_user->save();

        $role_regular_user = new Role;
        $role_regular_user->abbreviation = 'user';
        $role_regular_user->label = 'User';
        $role_regular_user->save();
    }
}
