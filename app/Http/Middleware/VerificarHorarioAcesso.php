<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App;
use Illuminate\Support\Facades\DB;

class VerificarHorarioAcesso
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $podeEntrar = 1;
        if( $request->user()->codNivel == 4 || $request->user()->codNivel == 5 ) {
            $table = $request->user()->codNivel == 4 ? DB::table('horariosacessoprodutor') : DB::table('horariosacessocliente');
            $sql = $table->select(DB::raw('CASE WHEN CURRENT_TIME BETWEEN 
                                            horasInicio AND horasFim 
                                            THEN TRUE
                                            ELSE FALSE
                                            END AS resultado'))
                        ->where(DB::raw('DAYOFWEEK(NOW())'),'=',DB::raw('dia'))
                        ->first();
            
            $podeEntrar = $sql->resultado;
        }
        
        if($podeEntrar === 0){
            Auth::logout();
            return redirect('forbidden');
        }

        return $next($request);
    }
}

