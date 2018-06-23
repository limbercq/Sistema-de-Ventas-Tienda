<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    protected $filable = [
        'idproveedor',
        'idusuario',
        'tipo_comprobante',
        'serie_comprobante',
        'num_comprobante',
        'fecha_hora',
        'impuesto',
        'total',
        'estado'
    ];

    public function usuario()
    {
        // retorna nuestro modelo user
        return $this->belongTo('App\User');
    }
    public function proveedor()
    {
        return $this->belongTo('App\Proveedor');
    }
}
