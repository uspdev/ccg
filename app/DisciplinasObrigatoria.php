<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DisciplinasObrigatoria extends Model
{
    public function curriculo()
    {
        return $this->belongsTo('App\Curriculo');
    }

    public function disciplinasObrigatoriasEquivalentes()
    {
        return $this->hasMany('App\DisciplinasObrigatoriasEquivalente');
    }
}
