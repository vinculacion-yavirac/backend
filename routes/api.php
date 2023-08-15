<?php
use App\Http\Controllers\AvanzeController;
use App\Http\Controllers\ActividadesController;
use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\BriefcaseController;
use App\Http\Controllers\BeneficiaryInstitutionsController;
use App\Http\Controllers\SolicitudeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\EmailsController;
use App\Http\Controllers\FilesController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectParticipantController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CatalogueController;

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
        Route::get('/', [UsersController::class, 'getUsers'])->middleware('permission:LEER_USUARIOS|LEER_DOCUMENTO');
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
        Route::get('/', [RolesController::class, 'getRoles'])->middleware('permission:LEER_ROLES|LEER_DOCUMENTO');
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


    //Portafolio
    Route::prefix('briefcase')->group(function () {
        $controller = BriefcaseController::class;

        Route::middleware('permission:LEER_PORTAFOLIO')->group(function () use ($controller) {
            Route::get('/', [$controller, 'getBriefcase']);
            Route::get('/{id}', [$controller, 'getBriefcaseById']);
            Route::get('/archived/list', [$controller, 'getArchivedBriefcase']);
            Route::get('/search/term/{term?}', [$controller, 'searchBriefcaseByTerm']);
            Route::get('/search/archived/term/{term?}', [$controller, 'searchArchivedBriefcaseByTerm']);
            Route::get('/filter/state/{state}', [$controller, 'filterBriefcaseByStatus']);
            Route::get('/search/state/aprobado/{term?}', [$controller, 'searchBriefcaseAprobadoByTerm']);
            Route::get('/search/state/pendiente/{term?}', [$controller, 'searchBriefcasePendienteByTerm']);
        });

        Route::middleware('permission:ARCHIVAR_PORTAFOLIO')->group(function () use ($controller) {
            Route::put('/archive/{id}', [$controller, 'archiveBriefcase']);
        });

        Route::middleware('permission:RESTAURAR_PORTAFOLIO')->group(function () use ($controller) {
            Route::put('/restore/{id}', [$controller, 'restoreBriefcase']);
        });

        Route::middleware('permission:ACTUALIZAR_PORTAFOLIO')->group(function () use ($controller) {
            Route::put('/update/{id}', [$controller, 'updateBriefcase']);
        });
        Route::middleware('permission:CREAR_PORTAFOLIO')->group(function () use ($controller) {
            Route::post('/create', [$controller, 'create']);
            Route::post('/upload/{idBriefcase}', [$controller, 'uploadFilesBriefcases']);
        });
    });

     //DOCUMENTO
     Route::prefix('document')->group(function () {
        Route::middleware('permission:LEER_DOCUMENTO')->group(function () {
            Route::get('/', [DocumentController::class, 'getDocuments']);
            Route::get('/{id}', [DocumentController::class, 'getDocumentsById']);
            Route::get('/archived/list', [DocumentController::class, 'getArchivedDocument']);
            Route::get('/search/term/{term?}', [DocumentController::class, 'searchDocumentsByTerm']);
            Route::get('/search/archived/term/{term?}', [DocumentController::class, 'searchDocumentsArchivedByTerm']);
        });
        
        Route::middleware('permission:ARCHIVAR_DOCUMENTO')->group(function () {
            Route::put('/archive/{id}', [DocumentController::class, 'archiveDocument']);
        });

        Route::middleware('permission:RESTAURAR_DOCUMENTO')->group(function () {
            Route::put('/restore/{id}', [DocumentController::class, 'restoreDocument']);
        });

        Route::middleware('permission:ACTUALIZAR_DOCUMENTO')->group(function () {
            Route::put('/update/{id}', [DocumentController::class, 'updateDocument']);
        });

        Route::middleware('permission:CREAR_DOCUMENTO')->group(function () {
            Route::post('/create', [DocumentController::class, 'createDocuments']);
        });
    });

    //SOLICITUD
    Route::prefix('solicitud')->group(function () {

        Route::middleware('permission:LEER_SOLICITUD')->group(function () {
            Route::get('/', [SolicitudeController::class, 'getSolicitudes']);
            Route::get('/{id}', [SolicitudeController::class, 'getSolicitudeById']);
            Route::get('/archived/list', [SolicitudeController::class, 'getArchivedSolicitude']);
            Route::get('/search/term/{term?}', [SolicitudeController::class, 'searchSolicitudeByTerm']);
            Route::get('/search/archived/term/{term?}', [SolicitudeController::class, 'searchArchivedSolicitudeByTerm']);
            Route::get('/filter/value/{value}', [SolicitudeController::class, 'filterSolicitudeByValue']);
            Route::get('/filter/status/{status}', [SolicitudeController::class, 'filterSolicitudeByStatus']);
            Route::get('/search/type/vinculacion/{term?}', [SolicitudeController::class, 'searchSolicitudeVinculacionByTerm']);
            Route::get('/search/type/certificado/{term?}', [SolicitudeController::class, 'searchCertificateByTerm']);
            Route::get('/search/status/pendiente/{term?}', [SolicitudeController::class, 'searchPendienteByTerm']);
            Route::get('/search/status/aprobado/{term?}', [SolicitudeController::class, 'searchAprobadoByTerm']);
            Route::get('/catalogues', [SolicitudeController::class, 'getAllCatalogues']);
        });

        Route::middleware('permission:ARCHIVAR_SOLICITUD')->group(function () {
            Route::put('/archive/{id}', [SolicitudeController::class, 'archiveSolicitud']);
        });

        Route::middleware('permission:RESTAURAR_SOLICITUD')->group(function () {
            Route::put('/restore/{id}', [SolicitudeController::class, 'restoreSolicitud']);
        });

        Route::middleware('permission:ACTUALIZAR_SOLICITUD')->group(function () {
            Route::put('/assign/{id}', [SolicitudeController::class, 'assignSolicitude']);
            Route::put('/aprovate-certificado/{id}', [SolicitudeController::class, 'aprovateCertificado']);
            Route::put('/disapprove-certificate/{id}', [SolicitudeController::class, 'disapproveCertificate']);
        });

        Route::middleware('permission:CREAR_SOLICITUD')->group(function () {
            Route::post('/create', [SolicitudeController::class, 'createSolicitude']); // Nueva ruta para crear una solicitud
        });
    });


    //PROYECTO
    Route::prefix('project')->group(function () {
        Route::middleware('permission:LEER_PROYECTO')->group(function () {
            Route::get('/', [ProjectController::class, 'getProject']);
            Route::get('/{id}', [ProjectController::class, 'getProjectById']);
            Route::get('/archived/list', [ProjectController::class, 'getArchivedProject']);
            Route::get('/search/term/{term?}', [ProjectController::class, 'searchProjectByTerm']);
            Route::get('/search/archived/term/{term?}', [ProjectController::class, 'searchArchivedProjectByTerm']);
        });
        Route::middleware('permission:CREAR_PROYECTO')->group(function () {
            Route::post('/create', [ProjectController::class, 'createProyect']);
            Route::put('updateProyectBeneficiaryInstitution/{id}', [ProjectController::class, 'updateProyectBeneficiaryInstitution']);
        });
        Route::middleware('permission:ARCHIVAR_PROYECTO')->group(function () {
            Route::put('/archive/{id}', [ProjectController::class, 'archiveProject']);

        });

        Route::middleware('permission:RESTAURAR_PROYECTO')->group(function () {
            Route::put('/restore/{id}', [ProjectController::class, 'restoreProject']);
        });
        Route::get('/foundation/{value}', [ProjectController::class, 'getProjectByFoundation']);
    });


    //Files
    Route::prefix('files')->group(function () {
        Route::get('/', [FilesController::class, 'getFiles']);
        Route::get('/{id}', [FilesController::class, 'getFileById']);
        Route::delete('/delete/{id}', [FilesController::class, 'deleteFileById']);
        Route::post('/upload/{idBriefcase}', [FilesController::class, 'uploadFiles']);
        Route::get('download/{portafolioId}/{documentoId}/{fileId}', [FilesController::class, 'downloadFile']);
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
        Route::get('/archived/list', [BeneficiaryInstitutionsController::class, 'getArchivedBeneficiaryInstitution']);
        Route::get('/{id}', [BeneficiaryInstitutionsController::class, 'getBeneficiaryInstitutionById'])->middleware('permission:LEER_FUNDACION');
        Route::put('/archive/{id}', [BeneficiaryInstitutionsController::class, 'archiveBeneficiaryInstitution']);
        Route::put('/restore/{id}', [BeneficiaryInstitutionsController::class, 'restaureBeneficiaryInstitution']);
        Route::get('/search/term/{term?}', [BeneficiaryInstitutionsController::class, 'searchBeneficiaryInstitutionByTerm'])->middleware('permission:LEER_FUNDACION');
        Route::get('/filter/state/{state}', [BeneficiaryInstitutionsController::class, 'filterBeneficiaryInstitutionByStatus']);
        Route::get('/search/state/activo/{term?}', [BeneficiaryInstitutionsController::class, 'searchActivasByTerm']);
        Route::get('/search/state/inactivo/{term?}', [BeneficiaryInstitutionsController::class, 'searchInactivaByTerm']);
        Route::post('/create', [BeneficiaryInstitutionsController::class, 'createFoundation'])->middleware('permission:CREAR_FUNDACION');
        Route::get('/projects/{value}', [BeneficiaryInstitutionsController::class, 'getFoundationByProject']);
    });


    //Integrantes detalle de la tabla project y solicitude
    Route::prefix('project-participant')->group(function () {

 
        Route::middleware('permission:LEER_PROYECTO')->group(function () {
            Route::get('/', [ProjectParticipantController::class, 'getAllProjectParticipants']);
            Route::get('/lista', [ProjectParticipantController::class, 'getAllProjectParticipantsTutor']);
            Route::get('/{participantId}', [ProjectParticipantController::class, 'getByParticipantId']);
            Route::get('by/{id}', [ProjectParticipantController::class, 'getById']);
            Route::get('/exist', [ProjectParticipantController::class, 'exist']);
        });
        Route::middleware('permission:CREAR_PROYECTO')->group(function () {
            Route::post('/create', [ProjectParticipantController::class, 'create']);
        });

        Route::middleware('permission:ACTUALIZAR_PROYECTO')->group(function () {
            Route::put('/{id}', [ProjectParticipantController::class, 'update']);

        });

        Route::middleware('permission:ELIMINAR_PROYECTO')->group(function () {
            Route::delete('/delete/{id}', [ProjectParticipantController::class, 'destroy']);
        });
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
    Route::prefix('activities')->group(function () {
        Route::get('/', [ActivitiesController::class, 'getAllActividades']);
        Route::get('/{id}', [ActivitiesController::class, 'getAllActividadesById']);
        Route::post('/create', [ActivitiesController::class, 'createActividades']);
        Route::put('/update/{id}', [ActivitiesController::class, 'updateActividades']);
        Route::delete('/delete/{id}', [ActivitiesController::class, 'deleteActividadesById']);
    });

    //Catalogo
    Route::prefix('catalogues')->group(function () {

        Route::middleware('permission:LEER_SOLICITUD')->group(function () {
            Route::get('/', [CatalogueController::class, 'getAllCatalogues']);
        });
    });
});


