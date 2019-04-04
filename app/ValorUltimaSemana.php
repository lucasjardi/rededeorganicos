<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ValorUltimaSemana extends Model
{
    protected $table = 'valores_produtos_ultima_semana';

    protected $primaryKey = 'codigo';

    public $timestamps = false;

    protected $fillable = [
    	'codigo',
    	'codProdutor',
    	'codProduto',
    	'valor'
    ];


    public function produto()
    {
    	return $this->belongsTo('App\Produto','codigo','codProduto');
    }
}
