<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produtor extends Model
{
    protected $table = 'produtor';

    protected $primaryKey = 'codigo';

    public $timestamps = false;

    protected $fillable = [
    	'codigo',
    	'codCertificado',
    	'codCidade',
    	'telefone',
    	'endereco'
    ];


    public function usuario()
    {
    	return $this->belongsTo('App\User','codigo','id');
    }

    public function certificado()
    {
        return $this->belongsTo('App\Certificado','codCertificado','codigo');
    }

    public function cidade()
    {
    	return $this->hasOne('App\Cidade','codigo','codCidade');
    }
}
