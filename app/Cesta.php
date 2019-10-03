<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cesta extends Model
{
   	protected $table = 'cesta';

    protected $fillable = [
    	'user_id',
    	'produto_id',
        'quantidade',
        'unidade',
        'subtotal',
        'codProdutor'
    ];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function produto()
    {
    	return $this->belongsTo('App\Produto','produto_id','codigo');
    }
}
