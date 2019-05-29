<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusPedido extends Model
{
    protected $table = 'statuspedido';

    protected $primaryKey = 'codigo';

    public $timestamps = false;

    protected $fillable = [
    	'codigo',
    	'descricao'
    ];

    public static function allAsArray()
    {
        $statuses = self::all();
        $statusesNome = array();
        foreach ($statuses as $status){
            $statusesNome[ $status->codigo ] = $status->descricao;
        }
        return $statusesNome;
    }
}
