<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Curriculo extends Model
{
    public function disciplinasOptativasEletivas()
    {
        return $this->hasMany('App\DisciplinasOptativasEletiva');
    }

    public function disciplinasObrigatorias()
    {
        return $this->hasMany('App\DisciplinasObrigatoria');
    }

    public function disciplinasLicenciaturas()
    {
        return $this->hasMany('App\DisciplinasLicenciatura');
    }
}
