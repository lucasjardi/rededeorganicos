<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'cliente';

    protected $primaryKey = 'codigo';

    public $timestamps = false;

    protected $fillable = [
    	'codigo',
    	'codCidade',
    	'cpf',
    	'telefone',
    	'endereco'
    ];


    public function usuario()
    {
    	return $this->belongsTo('App\User','codigo','id');
    }

    public function cidade()
    {
    	return $this->hasOne('App\Cidade','codigo','codCidade');
    }
}
