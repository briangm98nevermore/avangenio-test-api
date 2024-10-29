<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class ToroVacaGame extends Model
{

    use HasFactory, Notifiable;

    protected $table = 'toro_vaca_games';

    protected $fillable = [
        'nombre',
        'edad',
        'numeroPropuesto',
        'numeroIntentos',
        'estado',
        'evaluacion',
        'ranking'
    ];
}
