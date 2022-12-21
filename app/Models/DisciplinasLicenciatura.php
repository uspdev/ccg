<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisciplinasLicenciatura extends Model
{
    protected $table = 'DisciplinasLicenciaturas';
    
    public function curriculo()
    {
        return $this->belongsTo('App\Models\Curriculo');
    }

    public function disciplinasLicenciaturasEquivalentes()
    {
        return $this->hasMany('App\Models\DisciplinasLicenciaturasEquivalente', 'id', 'id_dis_lic');
    }
}
