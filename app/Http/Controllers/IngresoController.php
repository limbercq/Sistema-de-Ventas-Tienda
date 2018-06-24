<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Ingreso;
use App\DetalleIngreso;


class IngresoController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            //uniedo tabla user con personas
            $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
            // uniedo tabla user con roles
            ->join('users','ingresos.idusuario','=','users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
            'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
            'ingresos.estado', 'personas.nombre','users.usuario')
            ->orderBy('ingresos.id', 'desc')->paginate(6);
        }
        else{
            $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
            // uniedo tabla user con roles
            ->join('users','ingresos.idusuario','=','users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
            'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
            'ingresos.estado', 'personas.nombre','users.usuario')            
            ->where('ingresos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('ingresos.id', 'desc')->paginate(6);
        }
        

        return [
            'pagination' => [
                'total'        => $ingresos->total(),
                'current_page' => $ingresos->currentPage(),
                'per_page'     => $ingresos->perPage(),
                'last_page'    => $ingresos->lastPage(),
                'from'         => $ingresos->firstItem(),
                'to'           => $ingresos->lastItem(),
            ],
            'ingresos' => $ingresos
        ];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        
        try{
            DB::beginTransaction();

            // para que la fecha se capture de forma automatica 
            $mytime = Carbon::now('Ameriaca/Caracas');

            $ingresos = new Ingreso();
            $ingresos->idproveedor = $request->idproveedor;
            // le enviamos el usuario que se a autenticado
            $ingresos->idusuario = \Auth::user()->id;
            $ingresos->tipo_comprobante = $request->tipo_comprobante;
            $ingresos->serie_comprobate = $request->serie_comprobante;
            $ingresos->num_comprobante = $request->num_comprobante;
            $ingresos->fecha_hora = $mytime->toDateString();
            $ingresos->impuesto = $request->impuesto;
            $ingresos->total = $request->total;
            $ingresos->estado = 'Registrado';
            $ingresos->save();

            $detalles = $request->data; //array de detalles
            //Recorriendo todos los elementos

            foreach ($detalles as $ep => $det) {
                
                $detalle = new DetalleIngreso();
                $detalle->idingreso = $ingreso->id;
                $detalle->idarticulo = $det['idarticulo'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $det['precio'];
                $detalle->save();
            }

            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }

        
        
    }

    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $ingreso = Ingreso::findOrFail($request->id);
        $ingreso->estado = 'Anulado';
        $ingreso->save();
    }
}
