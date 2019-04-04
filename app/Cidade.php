<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
	protected $primaryKey = 'codigo';

   	protected $table = 'cidade';

    public $timestamps = false;

    protected $fillable = [
    	'uf',
    	'descricao'
    ];

}
