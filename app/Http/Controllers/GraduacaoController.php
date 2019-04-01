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

class GraduacaoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
        $this->repUnd = config('ccg.codUnd');
    }

    public function busca()
    {
        return view('graduacao.busca');
    }

    public function dadosAcademicos(Request $request)
    {       
        // É aluno de graduação ATIVO da unidade? 
        if (Graduacao::verifica($request->codpes, $this->repUnd)) {
            $dadosAcademicos = (object) array(
                'codpes'    => Graduacao::curso($request->codpes, $this->repUnd)['codpes'],
                'nompes'    => Graduacao::curso($request->codpes, $this->repUnd)['nompes'],
                'codcur'    => Graduacao::curso($request->codpes, $this->repUnd)['codcur'],
                'nomcur'    => Graduacao::curso($request->codpes, $this->repUnd)['nomcur'],
                'codhab'    => Graduacao::curso($request->codpes, $this->repUnd)['codhab'],
                'nomhab'    => Graduacao::curso($request->codpes, $this->repUnd)['nomhab'],
                'dtainivin' => Graduacao::curso($request->codpes, $this->repUnd)['dtainivin'],
                'codpgm'    => Graduacao::programa($request->codpes)['codpgm'],
            );
        } else {
            $msg = "O nº USP $request->codpes não pertence a um aluno ativo de Graduação nesta unidade.";
            $request->session()->flash('alert-danger', $msg);
            return redirect('/busca');
        }
        return view('graduacao.busca', compact('dadosAcademicos'));
    }

    public function creditos(Request $request, $codpes = null)
    {   
        # Gate para simular o acesso de aluno de graduação
        if ($codpes === null) {
            // Se é desenvolvimento, popula o usuário de teste
            // para secretaria
            if (config('ccg.envDev') === 'dev' and config('ccg.codpesAluno')) {
                $aluno = config('ccg.codpesAluno'); # desenvolvimento
                $gate = 'secretaria';
            // para o aluno logado
            } else {
                $aluno = Auth::user()->id; # produção
                $gate = 'alunos';
            }
        // para busca e currículo
        } else {
            $aluno = $codpes; # caso o método seja utilizado em /busca ou em /curriculos
        }

        # Verifica se é aluno ativo de graduação na unidade      
        if (Graduacao::verifica($aluno, $this->repUnd) == false) {
            $msg = "O nº USP $aluno não pertence a um aluno ativo de Graduação nesta unidade.";
            $request->session()->flash('alert-danger', $msg);

            return view('graduacao.busca');
        } else {
            # Curso e Habilitação do aluno
            $graduacaoCurso = Graduacao::curso($aluno, $this->repUnd);
            
            # Programa
            $graduacaoPrograma = Graduacao::programa($aluno);

            # Dados do aluno
            $dadosAluno = [
                'codpes' => $aluno,
                'nompes' => $graduacaoCurso['nompes'],
                'codema' => $graduacaoCurso['codema'],
                'codcur' => $graduacaoCurso['codcur'],
                'nomcur' => $graduacaoCurso['nomcur'],
                'codhab' => $graduacaoCurso['codhab'],
                'nomhab' => $graduacaoCurso['nomhab'],
                'dtainivin' => substr($graduacaoCurso['dtainivin'], 0, 4),
                'codpgm' => $graduacaoPrograma['codpgm']
            ];

            # Currículo do aluno
            $curriculo = Curriculo::where('codcur', $dadosAluno['codcur'])
                ->where('codhab', $dadosAluno['codhab'])
                ->whereYear('dtainicrl', substr($dadosAluno['dtainivin'], 0, 4))
                                    # ->whereYear('dtainicrl', substr('2000-01-01', 0, 4)) # teste
                ->get(); 

            # Verifica se o aluno pertence a um currículo cadastrado
            if ($curriculo->isEmpty()) {
                $msg = "O aluno $aluno - {$dadosAluno['nompes']} não pertence a um currículo cadastrado neste sistema.";
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
            $disciplinasConcluidas = Graduacao::disciplinasConcluidas($aluno, $this->repUnd);
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

            // dd(
            //     'Aluno', $dadosAluno,
            //     'Currículo', $curriculoAluno,
            //     # 'Obrigatórias', $disciplinasObrigatorias,
            //     # 'Obrigatórias Equivalentes', $disciplinasObrigatoriasEquivalentes,
            //     # 'Optativas Eletivas', $disciplinasOptativasEletivas,
            //     # 'Licenciaturas', $disciplinasLicenciaturas,
            //     # 'Licenciaturas Equivalentes', $disciplinasLicenciaturasEquivalentes,
            //     'Todas exigidas', $disciplinasCurriculo,
            //     'Concluídas', $disciplinasConcluidasCoddis,
            //     'Obrigatórias Concluídas', $disciplinasObrigatoriasConcluidas,
            //     'Obrigatóiras Faltam', $disciplinasObrigatoriasFaltam,
            //     'Obrigatórias Equivalentes Concluídas',
            //     'Optativas Eletivas Concluídas', $disciplinasOptativasEletivasConcluidas,
            //     'Total de Créditos Concluídos Optativas Eletivas', $numcredisoptelt,
            //     'Total de Créditos Faltam Optativas Eletivas', ($curriculoAluno['numcredisoptelt'] - $numcredisoptelt),                
            //     'Licenciaturas Concluídas', $disciplinasLicenciaturasConcluidas,
            //     'Licenciaturas Faltam', $disciplinasLicenciaturasFaltam,
            //     'Licenciaturas Equivalentes Concluídas',
            //     'Optativas Livres Concluídas', $disciplinasOptativasLivresConcluidas,
            //     'Total de Créditos Concluídos Optativas Livres', $numcredisoptliv
            // ); 

            return view(
                'aluno.creditos',
                compact(
                    'gate',
                    'dadosAluno',
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

    # Retorna o Gate
    public function getGate()
    {
        # Se APP_ENV = dev e CODPES_ALUNO não é vazio, desenvolvimento
        if (config('ccg.envDev') === 'dev' and !empty(config('ccg.codpesAluno'))) {
            $gate = 'secretaria';
        } else {
            $gate = 'alunos';
        }

        return $gate;
    }

    # Ajax Busca alunos ativos com parte do nome e preenche o campo Nº USP com o codpes
    public function buscaAlunos($parteNome)
    {
        $alunos = Graduacao::ativos($this->repUnd, $parteNome);
        return response($alunos);
    }

}
