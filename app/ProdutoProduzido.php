<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProdutoProduzido extends Model
{
    protected $table = 'prod_produzido';

    protected $primaryKey = 'codigo';

    protected $fillable = [
    	'codProdutor',
    	'codProduto',
    	'codUnidade',
        'valor'
    ];


    public function produtor()
    {
    	return $this->belongsTo('App\Produtor','codProdutor','codigo');
    }

    public function produto()
    {
    	return $this->belongsTo('App\Produto','codProduto','codigo');
    }

    public function user()
    {
    	return $this->belongsTo('App\User','codProdutor','id');
    }

    public function unidade()
    {
    	return $this->belongsTo('App\Unidade','codUnidade','codigo');
    }
}
