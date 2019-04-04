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

}
