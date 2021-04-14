<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    protected $table = 'inscripciones';

    protected $fillable = [
        'id_alumno'
        , 'id_curso'
        , 'fecha'
        , 'fec_activa'
        , 'fec_termina'
        , 'fec_cancela'
        , 'fec_vence'
        , 'estado'
        , 'resultado'
    ];
}
