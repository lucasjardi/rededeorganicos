<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Solicitacao extends Model
{
    use SoftDeletes;
    
    protected $table = 'solicitacoes';

    protected $primaryKey = 'codigo';

    protected $fillable = [
    	'name',
    	'email',
    	'password',
        'nivel',
        'codCidade',
        'telefone',
        'endereco'
    ];
}
