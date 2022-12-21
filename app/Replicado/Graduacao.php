<?php

namespace App\Replicado;

use Uspdev\Replicado\DB;
use Uspdev\Replicado\Graduacao as ReplicadoGraduacao;
use Uspdev\Replicado\Replicado as Config;

class Graduacao extends ReplicadoGraduacao
{
    /**
     * Verifica se o codpes é um aluno de graduação ativo na unidade
     */
    public static function verificarAluno($codpes)
    {
        $query = "SELECT *
            FROM LOCALIZAPESSOA
            WHERE codpes = convert(int,:codpes)
            AND tipvin = 'ALUNOGR'
            AND sitatl = 'A'
            AND codundclg IN (" . Config::getInstance()->codundclgs . ")";
        $param = [
            'codpes' => $codpes,
        ];
        $result = DB::fetchAll($query, $param);

        // dd($codpes, $result);

        return empty($result) ? false : true;
    }
}
