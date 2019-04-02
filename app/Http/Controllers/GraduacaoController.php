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

    public function creditos(Request $request, $codpes = null)
    {   
        # Obtem o nº USP do aluno a ser analisado 
        $aluno = Aluno::getAluno($request, $codpes);

        # Obtem os dados acadêmicos
        $dadosAcademicos = Aluno::getDadosAcademicos($request, $aluno);

        # Currículo do aluno
        $curriculo = Curriculo::where('codcur', $dadosAcademicos->codcur)
            ->where('codhab', $dadosAcademicos->codhab)
            ->whereYear('dtainicrl', substr($dadosAcademicos->dtainivin, 0, 4))
                                # ->whereYear('dtainicrl', substr('2000-01-01', 0, 4)) # teste
            ->get(); 

        # Verifica se o aluno pertence a um currículo cadastrado
        if ($curriculo->isEmpty()) {
            $msg = "O aluno $aluno - {$dadosAcademicos->nompes} não pertence a um currículo cadastrado neste sistema.";
            $request->session()->flash('alert-danger', $msg);
            $curriculos = Curriculo::all();

            return view('curriculos.index', compact('curriculos'));
        }                                       

        # Dados do Currículo do Aluno
        $curriculoAluno = [
            'id_crl' => $curriculo[0]['id'],
            'numcredisoptelt' => $curriculo[0]['numcredisoptelt'],
            'numcredisoptliv' => $curriculo[0]['numcredisoptliv'],
            'dtainicrl' => substr($curriculo[0]['dtainicrl'], 0, 4)
        ];

        # Disciplinas que o currículo exige
        $disciplinasObrigatorias = DisciplinasObrigatoria::where('id_crl', $curriculoAluno['id_crl'])
            ->orderBy('coddis', 'asc')
            ->get()
            ->toArray();
        $disciplinasObrigatoriasCoddis = array();
        foreach ($disciplinasObrigatorias as $disciplinaObrigatoria) {
            array_push($disciplinasObrigatoriasCoddis, $disciplinaObrigatoria['coddis']);
        }
        sort($disciplinasObrigatoriasCoddis);
        $disciplinasOptativasEletivas = DisciplinasOptativasEletiva::where('id_crl', $curriculoAluno['id_crl'])
            ->orderBy('coddis', 'asc')
            ->get()
            ->toArray();
        $disciplinasOptativasEletivasCoddis = array();
        foreach ($disciplinasOptativasEletivas as $disciplinaOptativaEletiva) {
            array_push($disciplinasOptativasEletivasCoddis, $disciplinaOptativaEletiva['coddis']);
        }
        sort($disciplinasOptativasEletivasCoddis);
        $disciplinasLicenciaturas = DisciplinasLicenciatura::where('id_crl', $curriculoAluno['id_crl'])
            ->orderBy('coddis', 'asc')
            ->get()
            ->toArray();
        $disciplinasLicenciaturasCoddis = array();
        foreach ($disciplinasLicenciaturas as $disciplinaLicenciatura) {
            array_push($disciplinasLicenciaturasCoddis, $disciplinaLicenciatura['coddis']);
        }
        sort($disciplinasLicenciaturasCoddis);

        # Obrigatórias equivalentes
        $disciplinasObrigatoriasEquivalentes = array();
        foreach ($disciplinasObrigatorias as $disciplinaObrigatoria) {
            # Consulta se tem equivalente
            $disciplinaObrigatoriaEquivalente = DisciplinasObrigatoriasEquivalente::where('id_dis_obr', $disciplinaObrigatoria['id'])
                ->orderBy('coddis', 'asc')
                ->get();
            # Se tem equivalentes
            if ($disciplinaObrigatoriaEquivalente->isEmpty() == false) {
                # Monta array com as equivalentes
                $y = 0;
                foreach ($disciplinaObrigatoriaEquivalente as $obrigatoriaEquivalente) {
                    $disciplinasObrigatoriasEquivalentes[$disciplinaObrigatoria['coddis']][$y] = $obrigatoriaEquivalente['coddis'];
                    $y++;
                }
            }
        }             

        # Licenciaturas equivalentes
        $disciplinasLicenciaturasEquivalentes = array();
        foreach ($disciplinasLicenciaturas as $disciplinaLicenciatura) {
            # Consulta se tem equivalente
            $disciplinaLicenciaturaEquivalente = DisciplinasLicenciaturasEquivalente::where('id_dis_lic', $disciplinaLicenciatura['id'])
                ->orderBy('coddis', 'asc')
                ->get();
            # Se tem equivalentes
            if ($disciplinaLicenciaturaEquivalente->isEmpty() == false) {
                # Monta array com as equivalentes
                $y = 0;
                foreach ($disciplinaLicenciaturaEquivalente as $licenciaturaEquivalente) {
                    $disciplinasLicenciaturasEquivalentes[$disciplinaLicenciatura['coddis']][$y] = $licenciaturaEquivalente['coddis'];
                    $y++;
                }
            }
        }            

        # Disciplinas concluidas
        $disciplinasConcluidas = Graduacao::disciplinasConcluidas($aluno, config('ccg.codUnd'));
        $disciplinasConcluidasCoddis = array();
        foreach ($disciplinasConcluidas as $disciplinaConcluida) {
            array_push($disciplinasConcluidasCoddis, $disciplinaConcluida['coddis']);
        }
        sort($disciplinasConcluidasCoddis);

        # Disciplinas Obrigatórias concluídas
        $disciplinasObrigatoriasConcluidas = array();
        foreach ($disciplinasConcluidas as $disciplinaConcluida) {
            foreach ($disciplinasObrigatorias as $disciplinaObrigatoria) {
                if ($disciplinaConcluida['coddis'] == $disciplinaObrigatoria['coddis']) {
                    array_push($disciplinasObrigatoriasConcluidas, $disciplinaConcluida['coddis']);
                }
            }
        }

        # Disciplinas Obrigatórias faltam
        $disciplinasObrigatoriasFaltam = array_diff($disciplinasObrigatoriasCoddis, $disciplinasObrigatoriasConcluidas);

        # Disciplinas Optativas Eletivas concluídas
        $disciplinasOptativasEletivasConcluidas = array();
        $numcredisoptelt = 0;
        foreach ($disciplinasConcluidas as $disciplinaConcluida) {
            foreach ($disciplinasOptativasEletivas as $disciplinaOptativaEletiva) {
                if ($disciplinaConcluida['coddis'] == $disciplinaOptativaEletiva['coddis']) {
                    array_push($disciplinasOptativasEletivasConcluidas, $disciplinaConcluida['coddis']);
                    # Total de Créditos Concluídos Optativas Eletivas
                    $numcredisoptelt += $disciplinaConcluida['creaul'];
                }
            }
        }

        # Disciplinas Licenciaturas concluídas
        $disciplinasLicenciaturasConcluidas = array();
        foreach ($disciplinasConcluidas as $disciplinaConcluida) {
            foreach ($disciplinasLicenciaturas as $disciplinaLicenciatura) {
                if ($disciplinaConcluida['coddis'] == $disciplinaLicenciatura['coddis']) {
                    array_push($disciplinasLicenciaturasConcluidas, $disciplinaLicenciatura['coddis']);
                }
            }
        }

        # Disciplinas Licenciaturas faltam
        $disciplinasLicenciaturasFaltam = array_diff($disciplinasLicenciaturasCoddis, $disciplinasLicenciaturasConcluidas);            

        # Disciplinas exigidas Currículo
        $disciplinasCurriculo = array_merge($disciplinasObrigatoriasCoddis, $disciplinasOptativasEletivasConcluidas, $disciplinasLicenciaturasCoddis);

        # Disciplinas Optativas Livres concluídas
        $disciplinasOptativasLivresConcluidas = array_diff($disciplinasConcluidasCoddis, $disciplinasCurriculo);

        # Total de Créditos Concluídos Optativas Livres
        $numcredisoptliv = 0;
        foreach ($disciplinasConcluidas as $disciplinaConcluida) {
            foreach ($disciplinasOptativasLivresConcluidas as $disciplinaOptativaLivre) {
                if ($disciplinaConcluida['coddis'] == $disciplinaOptativaLivre) {
                    # Total de Créditos Concluídos Optativas Livres
                    $numcredisoptliv += $disciplinaConcluida['creaul'];
                }
            }
        }

        $gate = Core::getGate();
        
        return view(
            'aluno.creditos',
            compact(
                'gate',
                'dadosAcademicos',
                'curriculoAluno',
                'disciplinasConcluidas',
                'disciplinasObrigatoriasConcluidas',
                'disciplinasObrigatoriasFaltam',
                'disciplinasOptativasEletivasConcluidas',
                'disciplinasOptativasLivresConcluidas',
                'numcredisoptelt',
                'disciplinasLicenciaturasConcluidas',
                'disciplinasLicenciaturasFaltam',
                'numcredisoptliv'
            )
        );
    }
}
