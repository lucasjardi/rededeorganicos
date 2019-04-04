<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'produto';

    protected $primaryKey = 'codigo';

    public $timestamps = false;

    protected $fillable = [
    	'codUnidade',
    	'codGrupo',
    	'descricao',
    	'nome',
    	'imagem',
    	'observacao1',
    	'observacao2',
    	'ativo'
    ];


    public function unidade()
    {
    	return $this->hasOne('App\Unidade','codigo','codUnidade');
    }

    public function grupo()
    {
    	return $this->hasOne('App\Grupo','codigo','codGrupo');
    }

    public function valorultimasemana($value='')
    {
        return $this->belongsTo('App\ValorUltimaSemana','codigo','codProduto');
    }
}
