<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class ToroVacaGame extends Model
{

    use HasFactory, Notifiable;

    protected $table = 'toro_vaca_games';

    protected $fillable = [
        'token',
        'api_key',
        'numeroPropuesto',
        'numeroIntentos',
        'estado',
        'evaluacion',
        'ranking'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
