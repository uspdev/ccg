<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlunosObservacoes extends Model
{
    protected $table = 'AlunosObservacoes';

    public function curriculo()
    {
        return $this->belongsTo('App\Curriculo');
    }
}
