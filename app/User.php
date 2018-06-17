<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'usuario', 'password', 'condicion', 'idrol'
    ];
    public $timestamps = false;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // protegiendo el password para que no se visualice 
    protected $hidden = [
        'password', 'remember_token',
    ];

    // que un usuario pertenece a un rol
    public function rol()
    {
        return $this->belongsTo('App\Rol');
    }

    // que un usario hace referencia a una persona
    public function persona()
    {
        return $this->belongsTo('App\Persona');
    }
}
