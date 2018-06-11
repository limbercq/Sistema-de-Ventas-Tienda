<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';
    protected $fillable = [
        'id','contacto','telefono_contacto'
    ];

    //en nuestra tabla no tiene los campos de timetamps
    public $timetamps = false;

    // un proveedor pertenece a una persona
    public function persona()
    {
        return $this->belongsTo('App\Persona');
    }
}
