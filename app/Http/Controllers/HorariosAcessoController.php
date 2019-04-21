<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class HorariosAcessoController extends Controller
{


    public function setHorarioAcessoCliente(Request $request)
    {
        $this->validate($request, [
            'diaInicio' => 'required',
            'horasInicio' => 'required',
            'diaFim' => 'required',
            'horasFim' => 'required',
        ]);

        DB::table('horariosacesso')
        ->where('nivel_id', 5)
        ->update([
            'diaInicio' => $request->diaInicio,
            'diaFim' => $request->diaFim,
            'horasInicio' => $request->horasInicio,
            'horasFim' => $request->horasFim,
        ]);
        
        if( $request->diaInicio < $request->diaFim || $request->diaInicio == $request->diaFim) {
            for ($i=$request->diaInicio; $i <= $request->diaFim; $i++) { 
                DB::table('horariosacessocliente')->where('dia', $i)->update([
                    'horasInicio' => $i == $request->diaInicio ? $request->horasInicio : '00:00:00',
                    'horasFim' =>  $i == $request->diaFim ? $request->horasFim : '23:59:59',
                ]);
            }
            for($i=1; $i<=7;$i++) {
                if(! (($request->diaInicio <= $i) && ($i <= $request->diaFim)) ) {
                    DB::table('horariosacessocliente')->where('dia', $i)->update([
                        'horasInicio' => '00:00:00',
                        'horasFim' =>  '00:00:00',
                    ]);
                }
            }

        } else {
            for($i=1; $i<=7;$i++) {
                if($i >= $request->diaInicio && $request->diaInicio<=7){
                    DB::table('horariosacessocliente')->where('dia', $i)->update([
                        'horasInicio' => $i == $request->diaInicio ? $request->horasInicio : '00:00:00',
                        'horasFim' =>  '23:59:59',
                    ]);
                }
                else if($i <= $request->diaFim) {
                    DB::table('horariosacessocliente')->where('dia', $i)->update([
                        'horasInicio' => $i == $request->diaInicio ? $request->horasInicio : '00:00:00',
                        'horasFim' =>  $i == $request->diaFim ? $request->horasFim : '23:59:59',
                    ]);
                }
                else {
                    DB::table('horariosacessocliente')->where('dia', $i)->update([
                        'horasInicio' => '00:00:00',
                        'horasFim' =>  '00:00:00',
                    ]);
                }
            }
        }

        \Session::flash('mensagem_sucesso','Horários Atualizados');
        
        return back();
        
    }

    public function setHorarioAcessoProdutor(Request $request)
    {
        $this->validate($request, [
            'diaInicio' => 'required',
            'horasInicio' => 'required',
            'diaFim' => 'required',
            'horasFim' => 'required',
        ]);

        DB::table('horariosacesso')
        ->where('nivel_id', 4)
        ->update([
            'diaInicio' => $request->diaInicio,
            'diaFim' => $request->diaFim,
            'horasInicio' => $request->horasInicio,
            'horasFim' => $request->horasFim,
        ]);
        
        if( $request->diaInicio < $request->diaFim || $request->diaInicio == $request->diaFim) {
            for ($i=$request->diaInicio; $i <= $request->diaFim; $i++) { 
                DB::table('horariosacessoprodutor')->where('dia', $i)->update([
                    'horasInicio' => $i == $request->diaInicio ? $request->horasInicio : '00:00:00',
                    'horasFim' =>  $i == $request->diaFim ? $request->horasFim : '23:59:59',
                ]);
            }
            for($i=1; $i<=7;$i++) {
                if(! (($request->diaInicio <= $i) && ($i <= $request->diaFim)) ) {
                    DB::table('horariosacessoprodutor')->where('dia', $i)->update([
                        'horasInicio' => '00:00:00',
                        'horasFim' =>  '00:00:00',
                    ]);
                }
            }

        } else {
            for($i=1; $i<=7;$i++) {
                if($i >= $request->diaInicio && $request->diaInicio<=7){
                    DB::table('horariosacessoprodutor')->where('dia', $i)->update([
                        'horasInicio' => $i == $request->diaInicio ? $request->horasInicio : '00:00:00',
                        'horasFim' =>  '23:59:59',
                    ]);
                }
                else if($i <= $request->diaFim) {
                    DB::table('horariosacessoprodutor')->where('dia', $i)->update([
                        'horasInicio' => $i == $request->diaInicio ? $request->horasInicio : '00:00:00',
                        'horasFim' =>  $i == $request->diaFim ? $request->horasFim : '23:59:59',
                    ]);
                }
                else {
                    DB::table('horariosacessoprodutor')->where('dia', $i)->update([
                        'horasInicio' => '00:00:00',
                        'horasFim' =>  '00:00:00',
                    ]);
                }
            }
        }

        \Session::flash('mensagem_sucesso','Horários Atualizados');
        
        return back();
        
    }

}
