<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'codNivel', 'ativo'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function nivel()
    {
        return $this->hasOne(Nivel::class,'codigo','codNivel');
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class,'codigo','id');
    }

    public function produtor()
    {
        return $this->hasOne(Produtor::class,'codigo','id');
    }
}
