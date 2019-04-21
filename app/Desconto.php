<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Desconto extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'destino_id',
        'porcentagem',
        'descricao'
    ];



    public function destino()
    {
        return $this->belongsTo('App\Destino','destino_id','codigo');
    }
}
