<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Permissions\HasPermissionsTrait;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\Role;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasPermissionsTrait;

    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
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
        'password' => 'hashed',
    ];

    public function roles(){
        return $this->belongstoMany(Role::class,'user_roles','user_id','role_id');
    }

    // public function checkRoles($roles){
    //     if(! is_array($roles)){
    //         $roles = [$roles];
    //     }

    //     if(! $this->hasAnyRole($roles)){
    //         auth()->logout();
    //         abort(404);
    //     }
    // }

    // public function hasAnyRole($roles): bool
    // {
    //     return (bool) $this->roles()->whereIn('name', $roles)->first();
    // }

    // public function hasRole($role): bool
    // {
    //     return (bool) $this->roles()->where('name', $role)->first();
    // }

    public function account_role()
    {
      return $this->hasOne(UserRole::class, 'id', 'user_id');
    }
}
