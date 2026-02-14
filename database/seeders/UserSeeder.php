<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = new Role();
        $admin->name = 'Admin';
        $admin->save();

        $admin = new Role();
        $admin->name = 'Doctor';
        $admin->save();

        $admin = new Role();
        $admin->name = 'Medical Officer';
        $admin->save();

        $admin = new Role();
        $admin->name = 'Nursing';
        $admin->save();

        $admin = new Role();
        $admin->name = 'Receptionist';
        $admin->save();


        $admin = new User();
        $admin->name = 'Admin';
        $admin->email = 'admin@gmail.com';
        $admin->password = Hash::make('123456');
        $admin->save();
      
        $role = Role::where('name', '=', 'Admin')->first();
        $admin->assignrole($role);

        $doctor = new User();
        $doctor->name = 'Doctor';
        $doctor->email = 'doctor@gmail.com';
        $doctor->password = Hash::make('123456');
        $doctor->save();
      
        $role = Role::where('name', '=', 'Doctor')->first();
        $doctor->assignrole($role);


        $medical_officer = new User();
        $medical_officer->name = 'Medical Officer';
        $medical_officer->email = 'medicalofficer@gmail.com';
        $medical_officer->password = Hash::make('123456');
        $medical_officer->save();
      
        $role = Role::where('name', '=', 'Medical Officer')->first();
        $medical_officer->assignrole($role);


        $nursing = new User();
        $nursing->name = 'Nursing';
        $nursing->email = 'nursing@gmail.com';
        $nursing->password = Hash::make('123456');
        $nursing->save();
      
        $role = Role::where('name', '=', 'Nursing')->first();
        $nursing->assignrole($role);
      

        $receptionist = new User();
        $receptionist->name = 'Receptionist';
        $receptionist->email = 'receptionist@gmail.com';
        $receptionist->password = Hash::make('123456');
        $receptionist->save();
      
        $role = Role::where('name', '=', 'Receptionist')->first();
        $receptionist->assignrole($role);

    }
}
