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
        //$roleOne->givePermissionTo(Permission::findById(1));
       // $roleOne->givePermissionTo(Permission::findById(7));
        $roleOne->givePermissionTo(Permission::findById(14));
        $roleOne->givePermissionTo(Permission::findById(15));
        $roleOne->givePermissionTo(Permission::findById(16));
        $roleOne->givePermissionTo(Permission::findById(17));
        $roleOne->givePermissionTo(Permission::findById(18));
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
        $roleOne->givePermissionTo(Permission::findById(46));
        $roleOne->givePermissionTo(Permission::findById(47));
        $roleOne->givePermissionTo(Permission::findById(48));
        $roleOne->givePermissionTo(Permission::findById(49));
        $roleOne->givePermissionTo(Permission::findById(50));
        $roleOne->givePermissionTo(Permission::findById(51));
        $roleOne->givePermissionTo(Permission::findById(52));
        $roleOne->givePermissionTo(Permission::findById(53));
        $roleOne->givePermissionTo(Permission::findById(54));
        $roleOne->givePermissionTo(Permission::findById(55));
        $roleOne->givePermissionTo(Permission::findById(56));
        $roleOne->givePermissionTo(Permission::findById(57));
        $roleOne->givePermissionTo(Permission::findById(58));
        $roleOne->givePermissionTo(Permission::findById(59));
        $roleOne->givePermissionTo(Permission::findById(60));
        $roleOne->givePermissionTo(Permission::findById(61));


        $roleTwo = Role::create(['name' => 'Docente Tutor']);
        $roleTwo->givePermissionTo(Permission::findById(14));
        $roleTwo->givePermissionTo(Permission::findById(15));
        $roleTwo->givePermissionTo(Permission::findById(16));
        $roleTwo->givePermissionTo(Permission::findById(17));
        $roleTwo->givePermissionTo(Permission::findById(19));
        $roleTwo->givePermissionTo(Permission::findById(20));
        $roleTwo->givePermissionTo(Permission::findById(21));
        $roleTwo->givePermissionTo(Permission::findById(30));
        $roleTwo->givePermissionTo(Permission::findById(31));
        $roleTwo->givePermissionTo(Permission::findById(32));
        $roleTwo->givePermissionTo(Permission::findById(33));
        $roleTwo->givePermissionTo(Permission::findById(34));
        $roleTwo->givePermissionTo(Permission::findById(35));
        $roleTwo->givePermissionTo(Permission::findById(36));
        $roleTwo->givePermissionTo(Permission::findById(37));


        $roleTwo = Role::create(['name' => 'Estudiante']);
        $roleTwo->givePermissionTo(Permission::findById(46));
        $roleTwo->givePermissionTo(Permission::findById(47));
        $roleTwo->givePermissionTo(Permission::findById(48));
        $roleTwo->givePermissionTo(Permission::findById(49));
        $roleTwo->givePermissionTo(Permission::findById(50));
        $roleTwo->givePermissionTo(Permission::findById(51));
        $roleTwo->givePermissionTo(Permission::findById(52));
        $roleTwo->givePermissionTo(Permission::findById(53));



        $roleThree = Role::create(['name' => 'Coordinador Carrera']);
        $roleThree->givePermissionTo(Permission::findById(1));
        $roleThree->givePermissionTo(Permission::findById(2));
        $roleThree->givePermissionTo(Permission::findById(3));
        $roleThree->givePermissionTo(Permission::findById(4));
        $roleThree->givePermissionTo(Permission::findById(5));
        $roleThree->givePermissionTo(Permission::findById(6));
        $roleThree->givePermissionTo(Permission::findById(7));
        $roleThree->givePermissionTo(Permission::findById(8));
        $roleThree->givePermissionTo(Permission::findById(9));
        $roleThree->givePermissionTo(Permission::findById(10));
        $roleThree->givePermissionTo(Permission::findById(11));
        $roleThree->givePermissionTo(Permission::findById(12));
        $roleThree->givePermissionTo(Permission::findById(13));
        $roleThree->givePermissionTo(Permission::findById(14));
        $roleThree->givePermissionTo(Permission::findById(22));
        $roleThree->givePermissionTo(Permission::findById(30));
        $roleThree->givePermissionTo(Permission::findById(38));
        $roleThree->givePermissionTo(Permission::findById(46));
        $roleThree->givePermissionTo(Permission::findById(54));









    }
}
