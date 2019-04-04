<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProdutorProduz extends Model
{
    protected $table = 'produtor_produz';

    protected $primaryKey = 'codigo';

    public $timestamps = false;

    protected $fillable = [
    	'codigo',
    	'codProdutor',
    	'codProduto'
    ];


    public function produto()
    {
    	return $this->belongsTo('App\Produto','codProduto','codigo');
    }
}
