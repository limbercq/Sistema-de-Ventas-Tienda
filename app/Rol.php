<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table= 'roles';
    protected $fillable = ['nombre','descripcion','condicion'];
    public $timestamps = false;
    
     //un rol puede esta relacionado, un rol puede tener varios usarios 1:n
     public function user()
     {
         return $this->hasMany('App\User');
     }
}
