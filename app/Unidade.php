<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unidade extends Model
{
    protected $table = 'unidade';

    protected $primaryKey = 'codigo';

    public $timestamps = false;

    protected $fillable = [
    	'descricao'
    ];

    public static function allAsArray()
    {
        $unidades = self::all();
        $unidadesNome = array();
        foreach ($unidades as $unidade){
            $unidadesNome[ $unidade->codigo ] = $unidade->descricao;
        }
        return $unidadesNome;
    }
}
