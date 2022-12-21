<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisciplinasObrigatoria extends Model
{
    protected $table = 'DisciplinasObrigatorias';
    
    public function curriculo()
    {
        return $this->belongsTo('App\Models\Curriculo');
    }

    public function disciplinasObrigatoriasEquivalentes()
    {
        return $this->hasMany('App\Models\DisciplinasObrigatoriasEquivalente', 'id', 'id_dis_obr');
    }
}
