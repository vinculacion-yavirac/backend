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

            'LEER_FUNDACION',
            'CREAR_FUNDACION',
            'ACTUALIZAR_FUNDACION',
            'ARCHIVAR_FUNDACION',
            'RESTAURAR_FUNDACION',
            'ELIMINAR_FUNDACION',
            'EXPORTAR_FUNDACION',
            'IMPORTAR_FUNDACION',


            'LEER_PROYECTO',
            'CREAR_PROYECTO',
            'ACTUALIZAR_PROYECTO',
            'ARCHIVAR_PROYECTO',
            'RESTAURAR_PROYECTO',
            'ELIMINAR_PROYECTO',
            'EXPORTAR_PROYECTO',
            'IMPORTAR_PROYECTO',

            'LEER_ESTUDIANTE',
            'CREAR_ESTUDIANTE',
            'ACTUALIZAR_ESTUDIANTE',
            'ARCHIVAR_ESTUDIANTE',
            'RESTAURAR_ESTUDIANTE',
            'ELIMINAR_ESTUDIANTE',
            'EXPORTAR_ESTUDIANTE',
            'IMPORTAR_ESTUDIANTE',

            'LEER_DOCUMENTO',
            'CREAR_DOCUMENTO',
            'ACTUALIZAR_DOCUMENTO',
            'ARCHIVAR_DOCUMENTO',
            'RESTAURAR_DOCUMENTO',
            'ELIMINAR_DOCUMENTO',
            'EXPORTAR_DOCUMENTO',
            'IMPORTAR_DOCUMENTO',

            'LEER_SOLICITUD_ESTUDIANTE',
            'CREAR_SOLICITUD_ESTUDIANTE',
            'EXPORTAR_SOLICITUD_ESTUDIANTE',
            'ACTULIZAR_PORTAFOLIO_ESTUDIANTE',

            'EXPORTAR_USUARIOS',
            'IMPORTAR_USUARIOS',
            'LEER_FUNDACION_TUTOR',
            'LEER_PORTAFOLIO_TUTOR',

        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
