<?php

namespace App\Permissions;

//use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

trait HasPermissionsTrait {

    public function givePermissionsTo(... $permissions) {

    $permissions = $this->getAllPermissions($permissions);
    if($permissions === null) {
      return $this;
    }

    $this->permissions()->saveMany($permissions);
    return $this;

  }

  public function withdrawPermissionsTo( ... $permissions ) {

    $permissions = $this->getAllPermissions($permissions);
    $this->permissions()->detach($permissions);
    return $this;

  }

  public function refreshPermissions( ... $permissions ) {

    $this->permissions()->detach();
    return $this->givePermissionsTo($permissions);

  }

  public function hasPermissionTo($permission) {

    return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);

  }

  public function hasRole( ... $roles ) {

    foreach ($roles as $role) {
      if ($this->roles->contains('role_id', $role)) {
        return true;
      }
    }
    return false;
  }

  public function roles() {
    return $this->belongsToMany(Role::class,'user_roles','account_id','role_id');
  }

  public function permissions() {
    return $this->belongsToMany(Permission::class,'users_permissions');
  }
  protected function hasPermission($permission) {
    return (bool) $this->permissions->where('code', $permission->slug)->count();
  }

  protected function getAllPermissions(array $permissions) {

   // return Permission::whereIn('slug',$permissions)->get();
    
  }
}