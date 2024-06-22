<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes;
    protected $appends = ['org_name', 'org_logo'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function Organization()
    {
        return $this->belongsTo(Organization::class, 'org_id', 'id');
    }

   /* public function permissions()
    {
        return $this->hasMany(UserPermission::class, 'user_id', 'id');

    }*/

   /* public function getUserPerAttribute()
    {
        $per = $this->permissions()->pluck('permission_id')->unique()->toArray();
        return $per;
    }*/

    public function getOrgNameAttribute()
    {
        return $this->Organization()->first()->name;
    }

    public function getOrgLogoAttribute()
    {
        $logo = $this->Organization()->first()->logo;
        return url('/public/storage/' . $logo);

    }

    /*public function getPerNameAttribute()
    {

        $per_id = $this->permissions()->pluck('permission_id')->unique();

        $per = Permission::whereIn('id', $per_id)->get();
        return $per;
    }*/

    /*public function getUserMenuAttribute()
    {
        $per_id = $this->permissions()->pluck('permission_id')->unique();
        $menuPer = Permission::whereIn('id', $per_id)->pluck('menu_id')->unique();
        $menu = Menu::whereIn('id', $menuPer)->get();


        foreach ($menu as $m) {

            $permissions = Permission::join('user_permissions', 'user_permissions.permission_id', '=', 'permissions.id')
                ->where('user_permissions.user_id', auth()->user()->id)
                ->where('permissions.permission_type', '=', 0)
                ->where('permissions.menu_id', $m->id)->orderBy('screen_order')->get();

            $m->sub_menu = $permissions;
        }
        return $menu;

    }*/




}
