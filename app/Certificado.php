<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Certificado extends Model
{
    //
    protected $primaryKey = 'numero';

    protected $table = 'certificado';

    public $timestamps = false;

    protected $fillable = [
    	'numero',
    	'codCertificadora',
    	'dataValidade'
    ];


    public function certificadora()
    {
    	return $this->belongsTo('App\Certificadora','codCertificadora','codigo');
    }


}
