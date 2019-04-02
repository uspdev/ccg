<?php

namespace App\Ccg;

use Illuminate\Http\Request;
use Uspdev\Replicado\Connection;
use Uspdev\Replicado\Graduacao;
use Auth;
use App\Ccg\Core;
use App\Curriculo;

class Aluno 
{
    /**
     * Classe para obter informações sobre alunos de graduação
     */
    
    public static function getAluno(Request $request, $codpes = null)
    {
        /**
         * Médoto que retorna o nº USP do aluno a ser anlisado
         * @param object $codpes
         * @return object $aluno
         */
        $gate = Core::getGate();
        if ($codpes === null and $gate == 'alunos') {
            // Se o aluno logou no sistema
            $aluno = Auth::user()->id;
        } elseif ($gate == 'secretaria') { 
            // Se a secretaria logou no sistema e clicou em Créditos do Aluno
            // Esta situação serve somente para simular o link Meus Créditos
            // que aparece somente para o aluno de graduação logado
            $aluno = config('ccg.codpesAluno');
        } else {
            // Quando recebe um nº USP
            $aluno = $codpes;
        }
        if (Graduacao::verifica($aluno, config('ccg.codUnd')) == false) {
            // Se não for aluno ativo de graduação na unidade
            $msg = "O nº USP $aluno não pertence a um aluno ativo de Graduação nesta unidade.";
            $request->session()->flash('alert-danger', $msg);
            return view('index');
        }
        return $aluno;
    }    
    
    public static function getDadosAcademicos(Request $request, $codpes = null) 
    {
        /**
         * Médoto que retorna os dados acadêmicos dos alunos de graduação
         * @param object $request 
         * @param int $codpes
         * @return object $dadosAcademicos
         */
        // Verifica se o nº USP vem do formulário de busca ($request)
        // ou de uma chamada do sistema ($codpes)
        $codpes = ($codpes === null) ? $request->codpes : $codpes;
        // É aluno de graduação ATIVO da unidade? 
        if (Graduacao::verifica($codpes, config('ccg.codUnd'))) {
            $dadosAcademicos = (object) array(
                'codpes'    => Graduacao::curso($codpes, config('ccg.codUnd'))['codpes'],
                'nompes'    => Graduacao::curso($codpes, config('ccg.codUnd'))['nompes'],
                'codcur'    => Graduacao::curso($codpes, config('ccg.codUnd'))['codcur'],
                'nomcur'    => Graduacao::curso($codpes, config('ccg.codUnd'))['nomcur'],
                'codhab'    => Graduacao::curso($codpes, config('ccg.codUnd'))['codhab'],
                'nomhab'    => Graduacao::curso($codpes, config('ccg.codUnd'))['nomhab'],
                'dtainivin' => Graduacao::curso($codpes, config('ccg.codUnd'))['dtainivin'],
                'codpgm'    => Graduacao::programa($codpes)['codpgm'],
            );
        } else {
            $msg = "O nº USP $codpes não pertence a um aluno ativo de Graduação nesta unidade.";
            $request->session()->flash('alert-danger', $msg);
            return redirect('/busca');
        }
        return $dadosAcademicos;
    }

    public static function getCurriculo(Request $request, $codpes)
    {
        /**
         * Médoto que retorna os dados curriculares dos alunos de graduação
         * @param object $request 
         * @param int $codpes
         * @return object $dadosAcademicos
         */
        # Currículo do aluno
        $curriculo = Curriculo::where('codcur', self::getDadosAcademicos($request, $codpes)->codcur)
            ->where('codhab', self::getDadosAcademicos($request, $codpes)->codhab)
            ->whereYear('dtainicrl', substr(self::getDadosAcademicos($request, $codpes)->dtainivin, 0, 4))
            ->get(); 
        # Verifica se o aluno pertence a um currículo cadastrado
        if ($curriculo->isEmpty()) {
            $msg = "O aluno $aluno - {self::getDadosAcademicos($request, $codpes)->nompes} não pertence a um currículo cadastrado neste sistema.";
            $request->session()->flash('alert-danger', $msg);
            $curriculos = Curriculo::all();
            return view('curriculos.index', compact('curriculos'));
        }        
        # Dados do Currículo do Aluno
        $curriculoAluno = (object) array(
            'id_crl' => $curriculo[0]->id,
            'numcredisoptelt' => $curriculo[0]->numcredisoptelt,
            'numcredisoptliv' => $curriculo[0]->numcredisoptliv,
            'dtainicrl' => substr($curriculo[0]->dtainicrl, 0, 4)
        );
        return $curriculoAluno;
    }
}