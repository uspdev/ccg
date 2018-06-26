<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DisciplinasLicenciaturasEquivalente extends Model
{
    protected $table = 'DisciplinasLicenciaturasEquivalentes';

    public function disciplinasLicenciaturas()
    {
        return $this->belongsTo('App\DisciplinasLicenciatura');
    }
}
