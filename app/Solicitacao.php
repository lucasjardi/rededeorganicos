<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solicitacao extends Model
{
    protected $table = 'solicitacoes';

    protected $primaryKey = 'codigo';

    public $timestamps = false;

    protected $fillable = [
    	'name',
    	'email',
    	'password',
    	'nivel'
    ];
}
