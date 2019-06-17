<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProdutoProduzido extends Model
{
    protected $table = 'prod_produzido';

    protected $primaryKey = 'codProdutor';

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
}
