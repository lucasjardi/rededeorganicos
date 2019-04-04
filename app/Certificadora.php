<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Certificadora extends Model
{
	protected $primaryKey = 'codigo';

    protected $table = 'certificadora';

    public $timestamps = false;

    protected $fillable = [
    	'nome'
    ];
}
