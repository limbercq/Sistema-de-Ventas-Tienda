<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Suppor\Facades\DB;

use App\Proveedor;
use App\Persona;


class ProveedorController extends Controller
{
    public function index(Request $request)
    {   // seguridad
        if(!$request->ajax()) return redirect('/');
        //listar todos los registros de la tabla categoria

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar ==''){
            //uniendo dos tablas
            $personas = Proveedor::join('personas','proveedores.id','=','personas.id')
            ->select('personas.id','personas.nombre','personas.tipo_documento',
            'personas.num_documento','personas.direccion','personas.telefono',
            'personas.email','proveedores.contacto','proveedores.telefono_contacto')
            ->orderBy('personas.id', 'desc')->paginate(3);
        }
        else{
            $personas = Persona::join('personas','proveedores.id','=','personas.id')
            ->select('personas.id','personas.nombre','personas.tipo_documento',
            'personas.num_documento','personas.direccion','personas.telefono',
            'personas.email','proveedores.contacto','proveedores.telefono_contacto')
            /* uniendo el nombre de la tabla mas el campo*/
            ->where('personas.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('personas.id', 'desc')->paginate(3);
        }
      
        return [
            'pagination' => [
                'total'        => $personas->total(),
                'current_page' => $personas->currentPage(),
                'per_page'     => $personas->perPage(),
                'last_page'    => $personas->lastPage(),
                'from'         => $personas->firstItem(),
                'to'           => $personas->lastItem(),

            ],
            'personas' => $personas
        ];
    }

    public function store(Request $request)
    {   
        if(!$request->ajax()) return redirect('/');
        try{
            // utilizando transacciones 
            DB:beginTransaction();

            //estamos registrando persona y al mismo tiempo un proveedor
            $persona =new Persona();
            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->save();

            $proveedor = new Proveedor();
            $proveedor->contacto = $request->contacto;
            $proveedor->telefono_contacto = $request->telefono_contacto;
            $proveedor->id = $request->id;
            $proveedor->save();

            DB::commit();
            
        }catch(Exception $e){
            // si habria un erro lo desase la transaccion
            DB::rollBack();

            console.log(e);
        }

        
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        try{
            // utilizando transacciones 
            DB:beginTransaction();

            // Buscar primero el proveedor a modificar
            $proveedor = Proveedor::finOrFail($request->id);
            //buscamos con ese id de proveedor que es el mismo de persona
            $persona = Persona::finOrFail($proveedor->id);

            //actualizar el registro persona y al mismo tiempo del proveedor
            
            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->save();

            $proveedor->contacto = $request->contacto;
            $proveedor->telefono_contacto = $request->telefono_contacto;           
            $proveedor->save();

            DB::commit();
            
        }catch(Exception $e){
            // si habria un erro lo desase la transaccion
            DB::rollBack();

            console.log(e);
        }
    }
}
