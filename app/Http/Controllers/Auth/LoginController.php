<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {       $this->validateLogin($request);

        // condicion para verificar si el usuario y contraseña que se ingreso son correctas 
        // y verificar si la condicion del usuario es 1 si esta activo

        if (Auth::attempt(['usuario' => $request->usuario,'password' => $request->password,'condicion'=>1])) {
            // si es correcta vamos a direccionar a la ruta main
            return redirect()->route('main');
        }
        //indicando el error que queremos mostrar
        // usando un traductor tans pero esto depende que leguaje hemos instalado 

        return back()->withErrors(['usuario' => trans('auth.failed')])
        // regresando lo que el usuario a ingresado
        ->withInput(request(['usuario']));      
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request,[
            'usuario' => 'required|string',
            'password' => 'required|string'
        ]);
    }
}
