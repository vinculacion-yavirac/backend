<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\EmailsController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\OfficialDocumentsController;
use App\Http\Controllers\FilesController;
use App\Http\Controllers\CommentsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí es donde puedes registrar rutas de API para tu aplicación. Estas
| rutas son cargadas por el RouteServiceProvider dentro de un grupo que
| tiene el middleware "api" asignado. Disfruta construyendo tu API!
|
*/

// Auth Routes
Route::prefix('auth')->group(function () {
    //ruta para loguear usuario
    Route::post('/login', [AuthController::class, 'login']);
    //Rutas protegidas
    Route::middleware('authentication')->group(function () {
        //ruta para obtener usuario autenticado
        Route::get('/profile', [AuthController::class, 'getProfile']);
        //ruta para cerrar sesión
        Route::delete('/logout', [AuthController::class, 'logout']);
    });

    //ruta para refrescar token
    Route::post('/refresh', [AuthController::class, 'refresh']);
});




//Rutas protegidas
Route::middleware('authentication')->group(function () {

    //Users
    Route::prefix('users')->group(function () {
        //ruta para obtener todos los usuarios
        Route::get('/', [UsersController::class, 'getUsers'])->middleware('permission:LEER_USUARIOS');
        //ruta para obtener usuario por id
        Route::get('/{id}', [UsersController::class, 'getUserById'])->middleware('permission:LEER_USUARIOS');
        //ruta para obtener usuarios por termino de búsqueda
        Route::get('/search/term/{term?}', [UsersController::class, 'searchUsuariosByTerm'])->middleware('permission:LEER_USUARIOS');
        //ruta para filtrar usuario por rol y buscar por termino
        Route::get('/search/role/{roleId}/term/{term?}', [UsersController::class, 'searchUsuariosByRoleTerm']);
        //ruta para crear usuario
        Route::post('/create', [UsersController::class, 'createUser'])->middleware('permission:CREAR_USUARIOS');
        //ruta para enviar correo con credenciales
        Route::post('/email/send-credentials', [EmailsController::class, 'sendEmail'])->middleware('permission:CREAR_USUARIOS');
        //ruta para actualizar contraseña
        Route::put('/update-password/{id}', [UsersController::class, 'updatePassword'])->middleware('permission:ACTUALIZAR_USUARIOS');
        //ruta para actualizar usuario
        Route::put('/update/{id}', [UsersController::class, 'updateUser'])->middleware('permission:ACTUALIZAR_USUARIOS');
        //ruta para obtener todos los usuarios archivados
        Route::get('/archived/list', [UsersController::class, 'getArchivedUsers'])->middleware('permission:LEER_USUARIOS');
        //ruta para obtener usuarios archivados por termino de búsqueda
        Route::get('/archived/search/term/{term?}', [UsersController::class, 'searchUsuariosArchivedByTerm'])->middleware('permission:LEER_USUARIOS');
        //ruta para archivar usuario
        Route::put('/archive/{id}', [UsersController::class, 'archiveUser'])->middleware('permission:ARCHIVAR_USUARIOS');
        //ruta para restaurar usuario
        Route::put('/restore/{id}', [UsersController::class, 'restoreUser'])->middleware('permission:RESTAURAR_USUARIOS');
        //ruta para eliminar usuario
        Route::delete('/delete/{id}', [UsersController::class, 'deleteUser'])->middleware('permission:ELIMINAR_USUARIOS');
        //ruta para validar si la identificación está disponible
        Route::get('/validate/identification/{identification}/{id?}', [UsersController::class, 'checkIdentificationIsAvailable']);
        //ruta para validar si el correo está disponible
        Route::get('/validate/email/{email}/{id?}', [UsersController::class, 'checkEmailIsAvailable']);
        //ruta para validar si la contraseña es igual a la actual
        Route::get('/validate/password/{password}/{id}', [UsersController::class, 'checkPasswordIsEqual']);
    });

    //Roles
    Route::prefix('roles')->group(function () {
        //ruta para obtener todos los roles
        Route::get('/', [RolesController::class, 'getRoles'])->middleware('permission:LEER_ROLES');
        //ruta para obtener rol por id
        Route::get('/{id}', [RolesController::class, 'getRoleById'])->middleware('permission:LEER_ROLES');
        //ruta para obtener roles por termino de búsqueda
        Route::get('/search/term/{term?}', [RolesController::class, 'searchRolesByTerm'])->middleware('permission:LEER_ROLES');
        //ruta para crear rol
        Route::post('/create', [RolesController::class, 'createRole'])->middleware('permission:CREAR_ROLES');
        //ruta para actualizar rol
        Route::put('/update/{id}', [RolesController::class, 'updateRole'])->middleware('permission:ACTUALIZAR_ROLES');
        //ruta para obtener todos los roles archivados
        Route::get('/archived/list', [RolesController::class, 'getArchivedRoles'])->middleware('permission:LEER_ROLES');
        //ruta para obtener roles archivados por termino de búsqueda
        Route::get('/archived/search/term/{term?}', [RolesController::class, 'searchRolesArchivedByTerm'])->middleware('permission:LEER_ROLES');
        //ruta para archivar rol
        Route::put('/archive/{id}', [RolesController::class, 'archiveRole'])->middleware('permission:ARCHIVAR_ROLES');
        //ruta para restaurar rol
        Route::put('/restore/{id}', [RolesController::class, 'restoreRole'])->middleware('permission:RESTAURAR_ROLES');
        //ruta para eliminar rol
        Route::delete('/delete/{id}', [RolesController::class, 'deleteRole'])->middleware('permission:ELIMINAR_ROLES');
        //ruta para validar si el nombre está disponible
        Route::get('/validate/name/{name}/{id?}', [RolesController::class, 'checkRolNameIsAvailable']);
    });

    //Permissions
    Route::prefix('permissions')->group(function () {
        //ruta para obtener todos los permisos
        Route::get('/', [PermissionsController::class, 'getPermissions'])->middleware('permission:LEER_PERMISOS');
        //ruta para obtener permisos por id de rol
        Route::get('/role/{value}', [PermissionsController::class, 'getPermissionsByRole'])->middleware('permission:LEER_PERMISOS');
    });

    //Statistics
    Route::prefix('statistics')->group(function () {
        //ruta para obtener las estadísticas
        Route::get('/{id}', [StatisticsController::class, 'getOficios']);
    });

    //OfficialDocuments
    Route::prefix('official-documents')->group(function () {
        //ruta para obtener todos los oficios
        Route::get('/', [OfficialDocumentsController::class, 'getOfficialDocuments']);
        //ruta para obtener un oficio por id
        Route::get('/{id}', [OfficialDocumentsController::class, 'getOfficialDocumentById']);
        //ruta para obtener una lista de oficios por termino
        Route::get('/search/term/{term?}', [OfficialDocumentsController::class, 'searchOfficialDocumentsByTerm']);
        //ruta para crear un oficio
        Route::post('/create', [OfficialDocumentsController::class, 'createOfficialDocument']);
        //ruta para actualizar un oficio
        Route::put('/update/{id}', [OfficialDocumentsController::class, 'updateOfficialDocument']);
        //ruta para archivar un oficio
        Route::put('/archive/{id}', [OfficialDocumentsController::class, 'archiveOfficialDocument']);
        //ruta para restaurar un oficio
        Route::put('/restore/{id}', [OfficialDocumentsController::class, 'restoreOfficialDocument']);
        //ruta para eliminar un oficio
        Route::put('/delete/{id}', [OfficialDocumentsController::class, 'deleteOfficialDocument']);
    });

    //Files
    Route::prefix('files')->group(function () {
        Route::get('/', [FilesController::class, 'getFiles']);
        Route::get('/{id}', [FilesController::class, 'getFileById']);
        Route::delete('/delete/{id}', [FilesController::class, 'deleteFileById']);
        Route::post('/upload/{id}', [FilesController::class, 'uploadFiles']);
        Route::get('/download/{id}', [FilesController::class, 'downloadFile']);
    });

    //Comments
    Route::prefix('comments')->group(function () {
        //ruta para obtener todos los comentarios de un oficio por id
        Route::get('/official_document/{id}', [CommentsController::class, 'getCommentsByOfficialDocument']);
        //ruta para agregar un comentario en un oficio
        Route::post('/create', [CommentsController::class, 'createComment']);
    });
});
