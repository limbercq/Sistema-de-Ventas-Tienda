<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rol;

class RolController extends Controller
{
    public function index(Request $request)
    {   // seguridad
        if(!$request->ajax()) return redirect('/');
        //listar todos los registros de la tabla categoria

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar ==''){
            $roles = Rol::orderBy('id', 'desc')->paginate(3);
        }
        else{
            $roles = Rol::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(5);
        }
      
        return [
            'pagination' => [
                'total'        => $roles->total(),
                'current_page' => $roles->currentPage(),
                'per_page'     => $roles->perPage(),
                'last_page'    => $roles->lastPage(),
                'from'         => $roles->firstItem(),
                'to'           => $roles->lastItem(),

            ],
            'roles' => $roles
        ];
    }

    //listamos todos con condicion 1 y lo devolvemos en un array
    public function selectRol()
    {
        $roles = Rol::where('condicion','=','1')
        ->select('id','nombre')
        ->orderBy('nombre','asc')->get();

        return['roles'=>$roles];
    }
}
