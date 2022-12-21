<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisciplinasObrigatoriasEquivalente extends Model
{
    protected $table = 'DisciplinasObrigatoriasEquivalentes';
    
    public function disciplinasObrigatorias()
    {
        return $this->belongsTo('App\Models\DisciplinasObrigatoria');
    }
}
