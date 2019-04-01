<?php

namespace App\Ccg;

use Illuminate\Http\Request;
use Uspdev\Replicado\Connection;
use Uspdev\Replicado\Graduacao;

class Aluno 
{
    /**
     * Classe para obter informações sobre alunos de graduação
     */
    public static function getDadosAcademicos($codpes, Request $request) 
    {
        /**
         * Médoto que retorna os dados acadêmicos dos alunos de graduação
         * @param int $codpes
         * @param object $request
         * @return object $dadosAcademicos
         */
        // É aluno de graduação ATIVO da unidade? 
        if (Graduacao::verifica($request->codpes, config('ccg.codUnd'))) {
            $dadosAcademicos = (object) array(
                'codpes'    => Graduacao::curso($request->codpes, config('ccg.codUnd'))['codpes'],
                'nompes'    => Graduacao::curso($request->codpes, config('ccg.codUnd'))['nompes'],
                'codcur'    => Graduacao::curso($request->codpes, config('ccg.codUnd'))['codcur'],
                'nomcur'    => Graduacao::curso($request->codpes, config('ccg.codUnd'))['nomcur'],
                'codhab'    => Graduacao::curso($request->codpes, config('ccg.codUnd'))['codhab'],
                'nomhab'    => Graduacao::curso($request->codpes, config('ccg.codUnd'))['nomhab'],
                'dtainivin' => Graduacao::curso($request->codpes, config('ccg.codUnd'))['dtainivin'],
                'codpgm'    => Graduacao::programa($request->codpes)['codpgm'],
            );
        } else {
            $msg = "O nº USP $request->codpes não pertence a um aluno ativo de Graduação nesta unidade.";
            $request->session()->flash('alert-danger', $msg);
            return redirect('/busca');
        }
        return $dadosAcademicos;
    }
}