<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlunosDispensas extends Model
{
    protected $table = 'AlunosDispensas';

    public function curriculo()
    {
        return $this->belongsTo('App\Curriculo');
    }
}
