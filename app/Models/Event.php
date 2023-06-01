<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $casts = [
        'items' => 'array'
    ];

    protected $dates = ['date'];

    protected $guarded = []; #necessario para dizer ao laravel que tudo que for enviado pelo POST pode ser atualizado, sem restricoes
    public function user(){
        #belongsTo = pertence à
        #Evento pertence a um usuario
        #retorna o dono do evento
        return $this->belongsTo('App\Models\User');
    }

    public function users(){
        #Usuarios que estao participando do evento
        return $this->belongsToMany('App\Models\User');
    }
}
