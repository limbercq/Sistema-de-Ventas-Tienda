<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{   /* se inclute el nombre de las columnas que van a recibir valores en nuestra tabla de personas de la base de datos*/
    protected $fillable = ['nombre','tipo_documento','num_documento','dirrecion','telefono','email'];

    // una persona esta relacionado de forma directa con un proveedor
    public function proveedor()
    {
        return $this->hasOne('App\Proveedor');
    }

    //una persona esta relacionada directamente con un usuario
    public function user()
    {
        return $this->hasOne('App\User');
    }
}
