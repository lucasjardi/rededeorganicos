<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unidade extends Model
{
    protected $table = 'unidade';

    protected $primaryKey = 'codigo';

    public $timestamps = false;

    protected $fillable = [
    	'descricao'
    ];
}
