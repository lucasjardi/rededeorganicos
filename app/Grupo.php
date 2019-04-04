<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $table = 'grupo';

    protected $primaryKey = 'codigo';

    public $timestamps = false;

    protected $fillable = [
    	'descricao'
    ];

}
