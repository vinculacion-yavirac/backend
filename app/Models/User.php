<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Person;
use App\Models\Solicitude;
use App\Models\Attendance;


class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'person',
        'active',
        'archived',
        'archived_at',
        'archived_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {

        $person = Person::find($this->person);

        return [
            'user' =>  [
                'names' => $person->names,
                'last_names' => $person->last_names,
                'email' => $this->email,
                'role' => $this->roles()->first()->name
            ],
            'permissions' => $this->getPermissionsViaRoles()->pluck('name')->toArray()
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class, 'person');
    }

    public function archived_by()
    {
        return $this->belongsTo(Person::class, 'archived_by');
    }

    public function solicitude()
    {
        return $this->belongsToMany(Solicitude::class);
    }
    public function attendance()
    {
        return $this->belongsToMany(Attendance::class);
    }
}
