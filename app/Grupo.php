<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $table = 'grupo';

    protected $primaryKey = 'codigo';

    public $timestamps = false;

    protected $fillable = [
    	'descricao'
    ];

    public static function allAsArray()
    {
        $grupos = self::all();
        $gruposNome = array();
        foreach ($grupos as $grupo){
            $gruposNome[ $grupo->codigo ] = $grupo->descricao;
        }

        return $gruposNome;
    }

}
