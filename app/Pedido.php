<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use SoftDeletes;
    
    protected $table = 'pedido';

    protected $primaryKey = 'codigo';

    public $timestamps = false;

    protected $fillable = [
    	'codCliente',
    	'codDestino',
    	'dataPedido',
    	'valor',
        'status',
        'descricao'
    ];

    protected $dates = [
        'dataPedido'
    ];

    public function usuario()
    {
        return $this->belongsTo('App\User','codCliente','id');
    }

    public function cliente()
    {
    	return $this->belongsTo('App\Cliente','codCliente','codigo');
    }

    public function destino()
    {
    	return $this->belongsTo('App\Destino','codDestino','codigo');
    }

    public function itens()
    {
        return $this->hasMany('App\ItemPedido','codPedido','codigo');
    }

    public function st()
    {
        return $this->belongsTo('App\StatusPedido','status','codigo');
    }
}
