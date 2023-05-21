<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\Module;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'LEER_USUARIOS',
            'CREAR_USUARIOS',
            'ACTUALIZAR_USUARIOS',
            'ARCHIVAR_USUARIOS',
            'RESTAURAR_USUARIOS',
            'ELIMINAR_USUARIOS',
            'LEER_ROLES',
            'CREAR_ROLES',
            'ACTUALIZAR_ROLES',
            'ARCHIVAR_ROLES',
            'RESTAURAR_ROLES',
            'ELIMINAR_ROLES',
            'LEER_PERMISOS',

            'LEER_PORTAFOLIO',
            'CREAR_PORTAFOLIO',
            'ACTUALIZAR_PORTAFOLIO',
            'ARCHIVAR_PORTAFOLIO',
            'RESTAURAR_PORTAFOLIO',
            'ELIMINAR_PORTAFOLIO',
            'EXPORTAR_PORTAFOLIO',
            'IMPORTAR_PORTAFOLIO',

            'LEER_SOLICITUD',
            'CREAR_SOLICITUD',
            'ACTUALIZAR_SOLICITUD',
            'ARCHIVAR_SOLICITUD',
            'RESTAURAR_SOLICITUD',
            'ELIMINAR_SOLICITUD',
            'EXPORTAR_SOLICITUD',
            'IMPORTAR_SOLICITUD',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
