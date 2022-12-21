<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curriculo extends Model
{
    protected $table = 'Curriculos';

    public function disciplinasObrigatorias()
    {
        return $this->hasMany('App\Models\DisciplinasObrigatoria', 'id', 'id_crl');
    }

    public function disciplinasOptativasEletivas()
    {
        return $this->hasMany('App\Models\DisciplinasOptativasEletiva', 'id', 'id_crl');
    }

    public function disciplinasLicenciaturas()
    {
        return $this->hasMany('App\Models\DisciplinasLicenciatura', 'id', 'id_crl');
    }

    public function alunosObservacoes()
    {
        return $this->hasMany('App\Models\AlunosObservacoes', 'id', 'id_crl');
    }   
    
    public function alunosDispensas()
    {
        return $this->hasMany('App\Models\AlunosDispensas', 'id', 'id_crl');
    } 
}
