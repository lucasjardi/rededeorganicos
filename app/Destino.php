<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Destino extends Model
{
    protected $table = 'destino';

    protected $primaryKey = 'codigo';

    public $timestamps = false;

    protected $fillable = [
    	'descricao',
    	'acrescimo'
    ];

    public static function allAsArray()
    {
        $destinos = self::all();
        $destinosNome = array();
        foreach ($destinos as $destino){
            $destinosNome[ $destino->codigo ] = $destino->descricao;
        }
        return $destinosNome;
    }

    public function desconto()
    {
        return $this->hasOne(Desconto::class,'destino_id');
    }

}
