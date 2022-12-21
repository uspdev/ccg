<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisciplinasOptativasEletiva extends Model
{
    protected $table = 'DisciplinasOptativasEletivas';
    
    public function curriculo()
    {
        return $this->belongsTo('App\Curriculo');
    }
}
