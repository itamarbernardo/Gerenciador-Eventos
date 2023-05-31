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

    public function user(){
        #belongsTo = pertence à
        #Evento pertence a um usuario
        return $this->belongsTo('App\Models\User');
    }
}
