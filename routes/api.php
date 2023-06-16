<?php
use App\Http\Controllers\AvanzeController;
use App\Http\Controllers\ActividadesController;

use App\Http\Controllers\BriefcaseController;
use App\Http\Controllers\BeneficiaryInstitutionsController;
use App\Http\Controllers\SolicitudeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\EmailsController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\FilesController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectParticipantController;

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

    //briefcase
    Route::prefix('briefcase')->group(function () {
        Route::get('/', [BriefcaseController::class, 'getBriefcase'])->middleware('permission:LEER_PORTAFOLIO');
        Route::get('/filter/state/{state}', [BriefcaseController::class, 'filterBriefcaseByStatus']);
        Route::get('/{id}', [BriefcaseController::class, 'getBriefcaseById'])->middleware('permission:LEER_PORTAFOLIO');
        Route::get('/search/term/{term?}', [BriefcaseController::class, 'searchBriefcaseByTerm'])->middleware('permission:LEER_PORTAFOLIO');
        Route::get('/search/state/aprobado/{term?}', [BriefcaseController::class, 'searchAprobadoByTerm']);
        Route::get('/search/state/pendiente/{term?}', [BriefcaseController::class, 'searchPendienteByTerm']);
        //ruta para crear un oficio
        Route::post('/create', [BriefcaseController::class, 'createBriefcase'])->middleware('permission:CREAR_PORTAFOLIO');
        //ruta para actualizar un oficio
        Route::put('/update/{id}', [BriefcaseController::class, 'updateBriefcase'])->middleware('permission:ACTUALIZAR_PORTAFOLIO');
        //ruta para archivar un oficio
        Route::put('/archive/{id}', [BriefcaseController::class, 'archiveBriefcase'])->middleware('permission:ARCHIVAR_PORTAFOLIO');
        //ruta para restaurar un oficio
        Route::put('/restore/{id}', [BriefcaseController::class, 'restoreBriefcase'])->middleware('permission:RESTAURAR_PORTAFOLIO');
        //ruta para eliminar un oficio
        Route::put('/delete/{id}', [BriefcaseController::class, 'restoreBriefcase'])->middleware('permission:ELIMINAR_PORTAFOLIO');
    });

    //Files
    Route::prefix('files')->group(function () {
        Route::get('/', [FilesController::class, 'getFiles']);
        Route::get('/{id}', [FilesController::class, 'getFileById']);
        Route::delete('/delete/{id}', [FilesController::class, 'deleteFileById']);
        Route::post('/upload/{id}', [FilesController::class, 'uploadFiles']);
        Route::get('/download/{id}', [FilesController::class, 'downloadFile']);
    });

    //Files
    Route::prefix('solicitud')->group(function () {
        Route::get('/', [SolicitudeController::class, 'getSolicitude']);
        Route::get('/{id}', [SolicitudeController::class, 'getSolicitudeById']);
        Route::get('/search/term/{term?}', [SolicitudeController::class, 'searchSolicitudeByTerm'])->middleware('permission:LEER_SOLICITUD');
        Route::put('/archive/{id}', [SolicitudeController::class, 'ArchiveSolicitud'])->middleware('permission:ARCHIVAR_SOLICITUD');
        Route::get('/archived/list', [SolicitudeController::class, 'getArchivedSolicitude'])->middleware('permission:LEER_SOLICITUD');
        Route::get('/search/archived/term/{term?}', [SolicitudeController::class, 'searchArchivedSolicitudeByTerm'])->middleware('permission:LEER_SOLICITUD');
        Route::put('/restore/{id}', [SolicitudeController::class, 'restaureSolicitud'])->middleware('permission:RESTAURAR_SOLICITUD');
        Route::put('/assign/{id}', [SolicitudeController::class, 'assignSolicitude']);
        Route::get('/filter/type/{status}', [SolicitudeController::class, 'filterSolicitudeByValue']);
        Route::get('/filter/status/{status}', [SolicitudeController::class, 'filterSolicitudeByStatus']);
        Route::get('/search/type/vinculacion/{term?}', [SolicitudeController::class, 'searchSolicitudeVinculacionByTerm']);
        Route::get('/search/type/certificado/{term?}', [SolicitudeController::class, 'searchCertificateByTerm']);
        Route::get('/search/status/pendiente/{term?}', [SolicitudeController::class, 'searchPendienteByTerm']);
        Route::get('/search/status/preaprobado/{term?}', [SolicitudeController::class, 'searchPreAprobadoByTerm']);
    });

    //Comments
    Route::prefix('comments')->group(function () {
        //ruta para obtener todos los comentarios de un oficio por id
        Route::get('/briefcaset/{id}', [CommentsController::class, 'getCommentsByBriefcase']);
        //ruta para agregar un comentario en un oficio
        Route::post('/create', [CommentsController::class, 'createComment']);
    });

    //fundacion
    Route::prefix('beneficiary-institution')->group(function () {
        //ruta para obtener todos los comentarios de un oficio por id
        Route::get('/', [BeneficiaryInstitutionsController::class, 'getBeneficiaryInstitution'])->middleware('permission:LEER_FUNDACION');
        Route::get('/{id}', [BeneficiaryInstitutionsController::class, 'getFoundationById'])->middleware('permission:LEER_FUNDACION');
        Route::get('/search/term/{term?}', [BeneficiaryInstitutionsController::class, 'searchFoundationByTerm'])->middleware('permission:LEER_FUNDACION');
        Route::post('/create', [BeneficiaryInstitutionsController::class, 'createFoundation'])->middleware('permission:CREAR_FUNDACION');
        Route::get('/projects/{value}', [BeneficiaryInstitutionsController::class, 'getFoundationByProject']);
    });

    Route::prefix('project')->group(function () {
        //ruta para obtener todos los comentarios de un oficio por id
        Route::get('/', [ProjectController::class, 'getProject'])->middleware('permission:LEER_PRTOYECTO');
        Route::get('/foundation/{value}', [ProjectController::class, 'getProjectByFoundation']);
        Route::get('/{id}', [ProjectController::class, 'getProjectById']);
    });


    //Integrantes detalle de la tabla project y solicitude
     Route::prefix('participant')->group(function () {
         Route::get('/', [ProjectParticipantController::class, 'getProyectParticipant']);
         Route::post('/create', [ProjectParticipantController::class, 'store']);
    });


     //AVANZES
       Route::prefix('avanze')->group(function () {
        Route::get('/', [AvanzeController::class, 'getAllAvanzes']);
        Route::get('/{id}', [AvanzeController::class, 'getAllAvanzesById']);
        Route::post('/create', [AvanzeController::class, 'createAvanzes']);
        Route::put('/update/{id}', [AvanzeController::class, 'updateAvanzes']);
        Route::delete('/delete/{id}', [AvanzeController::class, 'deleteAvanzeById']);
    });
    Route::prefix('actividades')->group(function () {
        Route::get('/', [ActividadesController::class, 'getAllActividades']);
        Route::get('/{id}', [ActividadesController::class, 'getAllActividadesById']);
        Route::post('/create', [ActividadesController::class, 'createActividades']);
        Route::put('/update/{id}', [ActividadesController::class, 'updateActividades']);
        Route::delete('/delete/{id}', [ActividadesController::class, 'deleteActividadesById']);
    });
});


