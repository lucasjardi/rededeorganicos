<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App;

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
        $now =  \Carbon\Carbon::now();
        $diaDaSemana = $now->dayOfWeek;
        $podeEntrar = App::environment('local') ? true : false;

        if(!$podeEntrar) {
            if (  $request->user()->codNivel == 5 ) { // Cliente
                if ( $diaDaSemana == 0 || $diaDaSemana == 1 ) {
           
                    $ultimoDomingo = $now->copy();
                    if( $diaDaSemana != 0 )
                        $ultimoDomingo = \Carbon\Carbon::createFromTimeStamp(strtotime("last Sunday", $now->timestamp));
                    $ultimoDomingo->hour = 12; $ultimoDomingo->minute = 0; $ultimoDomingo->second = 0;
    
                    $proximaSegunda = $now->copy();
                    if( $diaDaSemana != 1 )
                        $proximaSegunda = \Carbon\Carbon::createFromTimeStamp(strtotime("next Monday", $now->timestamp));
                    $proximaSegunda->hour = 22; $proximaSegunda->minute = 0; $proximaSegunda->second = 0;
    
                    if( $now->between($ultimoDomingo,$proximaSegunda) ) {
                       $podeEntrar = true;
                    }
    
                }
            } else if ( $request->user()->codNivel == 4 ) { // Produtor
                if ( ($diaDaSemana >= 4 && $diaDaSemana <= 6) || $diaDaSemana == 0 ) {
    
                    $ultimaQuinta = $now->copy();
                    if($diaDaSemana != 4)
                        $ultimaQuinta = \Carbon\Carbon::createFromTimeStamp(strtotime("last Thursday", $now->timestamp));
                    $ultimaQuinta->hour = 20;
        
                    $proximoDomingo =$now->copy();
                    if($diaDaSemana != 0)
                        $proximoDomingo = \Carbon\Carbon::createFromTimeStamp(strtotime("next Sunday", $now->timestamp));
                    $proximoDomingo->hour = 12;
        
                    if( $now->between($ultimaQuinta,$proximoDomingo) ) {
                        $podeEntrar = true;
                    }
                }
            } else if ( $request->user()->codNivel < 3 ) {
                $podeEntrar = true;
            }
        }

        if(!$podeEntrar){
            Auth::logout();
            return redirect('forbidden');
        }

        return $next($request);
    }
}

