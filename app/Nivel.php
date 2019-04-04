<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{
    protected $table = 'nivel';

    protected $primaryKey = 'codigo';

    public $timestamps = false;

    protected $fillable = [
    	'descricao'
    ];
}
