<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'Super Administrador']);
        $role->givePermissionTo(Permission::all());


        $roleOne = Role::create(['name' => 'Docente Vinculación']);
        $roleOne->givePermissionTo(Permission::findById(14));
        $roleOne->givePermissionTo(Permission::findById(15));
        $roleOne->givePermissionTo(Permission::findById(16));
        $roleOne->givePermissionTo(Permission::findById(17));
        $roleOne->givePermissionTo(Permission::findById(19));
        $roleOne->givePermissionTo(Permission::findById(20));
        $roleOne->givePermissionTo(Permission::findById(21));
        $roleOne->givePermissionTo(Permission::findById(22));
        $roleOne->givePermissionTo(Permission::findById(23));
        $roleOne->givePermissionTo(Permission::findById(24));
        $roleOne->givePermissionTo(Permission::findById(25));
        $roleOne->givePermissionTo(Permission::findById(26));
        $roleOne->givePermissionTo(Permission::findById(27));
        $roleOne->givePermissionTo(Permission::findById(28));
        $roleOne->givePermissionTo(Permission::findById(29));
        $roleOne->givePermissionTo(Permission::findById(30));
        $roleOne->givePermissionTo(Permission::findById(31));
        $roleOne->givePermissionTo(Permission::findById(32));
        $roleOne->givePermissionTo(Permission::findById(33));
        $roleOne->givePermissionTo(Permission::findById(34));
        $roleOne->givePermissionTo(Permission::findById(35));
        $roleOne->givePermissionTo(Permission::findById(36));
        $roleOne->givePermissionTo(Permission::findById(37));
        $roleOne->givePermissionTo(Permission::findById(38));
        $roleOne->givePermissionTo(Permission::findById(39));
        $roleOne->givePermissionTo(Permission::findById(40));
        $roleOne->givePermissionTo(Permission::findById(41));
        $roleOne->givePermissionTo(Permission::findById(42));
        $roleOne->givePermissionTo(Permission::findById(43));
        $roleOne->givePermissionTo(Permission::findById(44));
        $roleOne->givePermissionTo(Permission::findById(45));


        $roleTwo = Role::create(['name' => 'Docente Tutor']);
        $roleTwo->givePermissionTo(Permission::findById(1));
        $roleTwo->givePermissionTo(Permission::findById(2));
        $roleTwo->givePermissionTo(Permission::findById(3));
        $roleTwo->givePermissionTo(Permission::findById(4));
        $roleTwo->givePermissionTo(Permission::findById(5));
        $roleTwo->givePermissionTo(Permission::findById(6));
        $roleTwo->givePermissionTo(Permission::findById(7));
        $roleTwo->givePermissionTo(Permission::findById(8));
        $roleTwo->givePermissionTo(Permission::findById(9));
        $roleTwo->givePermissionTo(Permission::findById(10));
        $roleTwo->givePermissionTo(Permission::findById(11));
        $roleTwo->givePermissionTo(Permission::findById(12));
        $roleTwo->givePermissionTo(Permission::findById(13));
    }
}
