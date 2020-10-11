<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemPedido extends Model
{
    protected $table = 'item_pedido';

    protected $primaryKey = 'codigo';

    public $timestamps = false;

    protected $fillable = [
    	'codPedido',
    	'codProduto',
    	'quantidade',
    	'valorTotal',
        'descricao',
        'codProdutor',
        'available'
    ];


    public function pedido()
    {
    	return $this->belongsTo('App\Pedido','codPedido','codigo');
    }

    public function produto()
    {
    	return $this->belongsTo('App\Produto','codProduto','codigo');
    }

    public function produtor()
    {
    	return $this->belongsTo('App\Produtor','codProdutor','codigo');
    }

    public function user()
    {
    	return $this->belongsTo('App\User','codProdutor','id');
    }
}
