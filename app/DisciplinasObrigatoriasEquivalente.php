<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DisciplinasObrigatoriasEquivalente extends Model
{
    protected $table = 'DisciplinasObrigatoriasEquivalentes';
    
    public function disciplinasObrigatorias()
    {
        return $this->belongsTo('App\DisciplinasObrigatoria');
    }
}
