<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DisciplinasLicenciaturasEquivalente extends Model
{
    public function disciplinasLicenciaturas()
    {
        return $this->belongsTo('App\DisciplinasLicenciatura');
    }
}
