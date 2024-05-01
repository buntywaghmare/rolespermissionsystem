<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // all permissions
        $permissions =[
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete'
        ];

        // insert all permissions into table
        foreach($permissions as $permission){
            Permission::create([
                'name'=>$permission,
                'guard_name'=>'web'
            ]);
        }
    }
}
