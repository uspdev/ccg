<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DisciplinasLicenciatura extends Model
{
    public function curriculo()
    {
        return $this->belongsTo('App\Curriculo');
    }

    public function disciplinasLicenciaturasEquivalentes()
    {
        return $this->hasMany('App\DisciplinasLicenciaturasEquivalente');
    }
}
