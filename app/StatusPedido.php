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
}
