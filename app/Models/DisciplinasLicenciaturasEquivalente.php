<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class DisciplinasLicenciaturasEquivalente extends Model
{
    protected $table = 'DisciplinasLicenciaturasEquivalentes';

    public function disciplinasLicenciaturas()
    {
        return $this->belongsTo('App\Models\DisciplinasLicenciatura');
    }
}
