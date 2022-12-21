<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlunosObservacoes extends Model
{
    protected $table = 'AlunosObservacoes';

    public function curriculo()
    {
        return $this->belongsTo('App\Models\Curriculo');
    }    
}
