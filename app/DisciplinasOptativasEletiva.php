<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DisciplinasOptativasEletiva extends Model
{
    public function curriculo()
    {
        return $this->belongsTo('App\Curriculo');
    }
}
