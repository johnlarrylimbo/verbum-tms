<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = new User;
        $admin->name = 'App Administrator';
        $admin->username = 'admin';
        $admin->email = 'admin@uic.edu.ph';
        $admin->or_user_initial = 'AAdmin';
        $admin->password = hash::make('letmein');
        $admin->save();
        $admin->roles()->attach(Role::where('label', 'admin')->first());

        $admin = new User;
        $admin->name = 'App User';
        $admin->username = 'user';
        $admin->email = 'user@uic.edu.ph';
        $admin->or_user_initial = 'AUser';
        $admin->password = hash::make('letmein');
        $admin->save();
        $admin->roles()->attach(Role::where('label', 'user')->first());
    }
}
