<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use Uspdev\Replicado\Connection; 
use Uspdev\Replicado\Graduacao; 
use App\Curriculo;
use App\DisciplinasObrigatoria;
use App\DisciplinasOptativasEletiva;
use App\DisciplinasLicenciatura;
use App\DisciplinasObrigatoriasEquivalente;
use App\DisciplinasLicenciaturasEquivalente;
use Carbon;
use Auth;
use App\Ccg\Aluno;
use App\Ccg\Core;

class GraduacaoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function search()
    {
        /**
         * Médoto que retorna o formulário de busca de aluno
         */
        return view('graduacao.busca');
    }

    public function searchAlunos($parteNome)
    {
        /**
         * Médoto que retorna o response/ajax com alunos ativos de graduação filtrando por parte do nome
         * e preenche o campo Nº USP com o codpes
         * @param string $parteNome
         * @return response $alunos
         */
        $alunos = Graduacao::ativos(config('ccg.codUnd'), $parteNome);
        return response($alunos);
    }    

    public function dadosAcademicos(Request $request)
    {       
        /**
         * Médoto que retorna os dados acadêmicos do aluno
         * @param object $request
         * @return object $dadosAcademicos
         */
        $dadosAcademicos = Aluno::getDadosAcademicos($request, $request->codpes);
        return view('graduacao.busca', compact('dadosAcademicos'));
    }

    public static function creditos(Request $request, $view = 'aluno.creditos', $codpes = null)
    {   
        /**
         * Médoto que retorna os creditos e disciplinas que faltam do aluno de graduação
         * @param object $request
         * @param string $view
         * @param int $codpes
         * @return view $view         
         */
        # Obtém o nº USP do aluno a ser analisado 
        $aluno = Aluno::getAluno($request, $codpes);
        # Obtém os dados acadêmicos
        $dadosAcademicos = Aluno::getDadosAcademicos($request, $aluno);
        # Obtém o currículo do aluno
        $curriculoAluno = Aluno::getCurriculo($request, $aluno);                
        # Obtém as discplinas obrigatórias no currículo do aluno
        $disciplinasObrigatorias = Aluno::getDisciplinasObrigatorias($curriculoAluno->id_crl);
        # Obtém as discplinas optativas eletivas no currículo do aluno
        $disciplinasOptativasEletivas = Aluno::getDisciplinasOptativasEletivas($curriculoAluno->id_crl);
        # Obtém as discplinas licenciaturas no currículo do aluno
        $disciplinasLicenciaturas = Aluno::getDisciplinasLicenciaturas($curriculoAluno->id_crl);
        # Obtém as discplinas obrigatórias equivalentes no currículo do aluno       
        $disciplinasObrigatoriasEquivalentes = Aluno::getDisciplinasObrigatoriasEquivalentes($curriculoAluno->id_crl);
        # Obtém as discplinas licenciaturas equivalentes no currículo do aluno
        $disciplinasLicenciaturasEquivalentes = Aluno::getDisciplinasLicenciaturasEquivalentes($curriculoAluno->id_crl);
        # Obtém as discplinas concluídas do aluno
        $disciplinasConcluidas = Aluno::getDisciplinasConcluidas($aluno);
        # Obtém as discplinas obrigatórias concluídas do aluno
        $disciplinasObrigatoriasConcluidas = Aluno::getDisciplinasObrigatoriasConcluidas($aluno, $curriculoAluno->id_crl);
        # Obtém as disciplinas Obrigatórias que faltam
        $disciplinasObrigatoriasFaltam = array_diff($disciplinasObrigatorias, $disciplinasObrigatoriasConcluidas);
        # Obtém as discplinas optativas eletivas concluídas do aluno
        $disciplinasOptativasEletivasConcluidas = Aluno::getDisciplinasOptativasEletivasConcluidas($aluno, $curriculoAluno->id_crl);   
        # Obtém o total de créditos nas disciplinas optativas eletivas concluídas
        $numcredisoptelt = Aluno::getTotalCreditosDisciplinasOptativasEletivasConcluidas($aluno, $curriculoAluno->id_crl);;
        # Obtém as discplinas licenciaturas concluídas do aluno
        $disciplinasLicenciaturasConcluidas = Aluno::getDisciplinasLicenciaturasConcluidas($aluno, $curriculoAluno->id_crl);
        # Obtém as disciplinas licenciaturas faltam
        $disciplinasLicenciaturasFaltam = array_diff($disciplinasLicenciaturas, $disciplinasLicenciaturasConcluidas);            
        # Obtém as disciplinas exigidas no currículo
        $disciplinasCurriculo = array_merge($disciplinasObrigatorias, $disciplinasOptativasEletivasConcluidas, $disciplinasLicenciaturas);
        # Adiciona as disciplinas concluídas por equivalência em disciplinas obrigatórias ou licenciaturas concluídas
        $disciplinasConcluidasPorEquivalencia = array_diff($disciplinasConcluidas, $disciplinasCurriculo);
        foreach ($disciplinasConcluidasPorEquivalencia as $disciplinaConcluidaPorEquivalencia) {
            // descobrir se é obrigatoria ou licenciatura
            // adicionar em obrigatória concluida ou licenciatura concluida
        }
        # Obtém as disciplinas optativas livres concluídas                
        if ($curriculoAluno->numcredisoptliv == 0) {
            $disciplinasOptativasLivresConcluidas = Array();
        } else {
            $disciplinasOptativasLivresConcluidas = array_diff($disciplinasConcluidas, $disciplinasCurriculo);
        }
        # Obtém o total de créditos nas disciplinas optativas livres concluídas
        $numcredisoptliv = Aluno::getTotalCreditosDisciplinasOptativasLivresConcluidas($aluno, $curriculoAluno->id_crl, $disciplinasOptativasLivresConcluidas);
        # Obtém as disciplinas concluídas diretamente do replicado
        $disciplinasConcluidas = Graduacao::disciplinasConcluidas($aluno, config('ccg.codUnd'));
        # Obtém o gate para chavear o perfil entre secretaria e aluno
        $gate = Core::getGate();

        return view($view, compact(
                'gate', 'dadosAcademicos', 'curriculoAluno', 'disciplinasConcluidas', 'disciplinasObrigatoriasConcluidas',
                'disciplinasObrigatoriasFaltam', 'disciplinasOptativasEletivasConcluidas', 'disciplinasOptativasLivresConcluidas',
                'numcredisoptelt', 'disciplinasLicenciaturasConcluidas', 'disciplinasLicenciaturasFaltam', 'numcredisoptliv'
            )
        );
    }
}
