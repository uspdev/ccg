<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Uspdev\Replicado\Connection;
use Uspdev\Replicado\Graduacao;
use App\Curriculo;
use App\DisciplinasObrigatoria;
use App\DisciplinasOptativasEletiva;
use App\DisciplinasLicenciatura;
use Carbon;
use Auth;

class GraduacaoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
        $this->repUnd   = env('REPLICADO_CODUND');
    }
    
    public function busca()
    {
        return view('graduacao.busca');
    }
    
    public function buscaReplicado(Request $request)
    {
        // É aluno de graduação ATIVO da unidade? 
        if (Graduacao::verifica($request->codpes, $this->repUnd)) {
            // Retorna os dados acadêmicos
	        $graduacaoPrograma = Graduacao::programa($request->codpes);
            $graduacaoCurso = Graduacao::curso($request->codpes, $this->repUnd);  
        } else {
            $msg = "O nº USP $request->codpes não pertence a um aluno ativo de Graduação nesta unidade."; 
            $request->session()->flash('alert-danger', $msg);
            
            return redirect('/busca');
        }

        return view('graduacao.busca', compact('graduacaoCurso', 'graduacaoPrograma'));
    }

    public function creditos()
    {
        $gate = $this->getGate();
        if ($gate === 'secretaria') {
            $aluno = env('CODPES_ALUNO');
        } else {
            $aluno = Auth::user()->id;
        }

        # Retorna a Situação do Aluno
        // $situacaoAluno = self::situacaoAluno($aluno);

        # Curso e Habilitação do aluno
        $graduacaoCurso = Graduacao::curso($aluno, $this->repUnd);

        # Currículo do aluno
        $curriculo = Curriculo::where('codcur', $graduacaoCurso['codcur'])
                                ->where('codhab', $graduacaoCurso['codhab'])
                                ->whereYear('dtainicrl', substr($graduacaoCurso['dtainivin'], 0, 4))
                                ->get();  
        
        # Quantidade de Optativas Eletivas
        $numcredisoptelt = $curriculo[0]['numcredisoptelt'];

        # Quantidade de Optativas Livres
        $numcredisoptliv = $curriculo[0]['numcredisoptliv'];

        # Disciplinas que o currículo exige
        $disciplinasObrigatorias = DisciplinasObrigatoria::where('id_crl', $curriculo[0]['id'])
                                                            ->orderBy('coddis', 'asc')
                                                            ->get(['coddis'])
                                                            ->toArray();
        $disciplinasOptativasEletivas = DisciplinasOptativasEletiva::where('id_crl', $curriculo[0]['id'])
                                                            ->orderBy('coddis', 'asc')
                                                            ->get(['coddis'])
                                                            ->toArray();
        $disciplinasLicenciaturas = DisciplinasLicenciatura::where('id_crl', $curriculo[0]['id'])
                                                            ->orderBy('coddis', 'asc')
                                                            ->get(['coddis'])
                                                            ->toArray();

        # Junção das disciplinas que o currículo exige
        $disciplinasJuntas = array_merge($disciplinasObrigatorias, $disciplinasOptativasEletivas, $disciplinasLicenciaturas);

        # Ordena as disciplinas que o currículo exige
        $disciplinasCurriculo = array();
        foreach ($disciplinasJuntas as $disciplinaCurriculo) {
            array_push($disciplinasCurriculo, $disciplinaCurriculo['coddis']);
        }
        sort($disciplinasCurriculo);

        # Disciplinas já concluidas
        $disciplinasConcluidas = Graduacao::disciplinasConcluidas($aluno, $this->repUnd);
        $disciplinasConcluidasCoddis = array();
        foreach ($disciplinasConcluidas as $disciplinaConcluida) {
            array_push($disciplinasConcluidasCoddis, $disciplinaConcluida['coddis']);
        }
        sort($disciplinasConcluidasCoddis);
      
        # Separa as disciplinas que faltam
        $disciplinasFaltam = array_diff($disciplinasCurriculo, $disciplinasConcluidasCoddis);

        # Disciplinas optativas livres 
        $disciplinasOptativasLivres = array_diff($disciplinasConcluidasCoddis, $disciplinasCurriculo);

        # Total de créditos de concluídos de Discplinas Optativas Livres
        $totnumcredisoptliv = 0;
        foreach ($disciplinasConcluidas as $disciplinaConcluida) {
            if (in_array($disciplinaConcluida['coddis'], $disciplinasOptativasLivres)) {
                $totnumcredisoptliv += $disciplinaConcluida['creaul'];
            }
        }

        # Programa
        $graduacaoPrograma = Graduacao::programa($aluno);
       
        return view('aluno.creditos', 
            compact('graduacaoCurso', 
                'gate', 
                'disciplinasCurriculo', 
                'disciplinasConcluidas', 
                'disciplinasOptativasLivres', 
                'disciplinasFaltam',
                'graduacaoPrograma',
                'numcredisoptelt',
                'numcredisoptliv',
                'totnumcredisoptliv'
        ));
    }

    # Retorna o Gate
    public function getGate()
    {
        # Se APP_ENV = dev e CODPES_ALUNO não é vazio, desenvolvimento
        if (env('APP_ENV') === 'dev' and !empty(env('CODPES_ALUNO'))) { 
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

     /**
     * Método para trazer as disciplinas, status e créditos concluídos
     *
     * @param Int $codpes
     * @return void
     */   
    public function situacaoAluno($aluno) {
        # Curso e Habilitação do aluno
        $graduacaoCurso = Graduacao::curso($aluno, $this->repUnd);
        
        # Currículo do aluno
        $curriculo = Curriculo::where('codcur', $graduacaoCurso['codcur'])
                                ->where('codhab', $graduacaoCurso['codhab'])
                                ->whereYear('dtainicrl', substr($graduacaoCurso['dtainivin'], 0, 4))
                                ->get();  
        
        # Quantidade de Optativas Eletivas
        $numcredisoptelt = $curriculo[0]['numcredisoptelt'];

        # Quantidade de Optativas Livres
        $numcredisoptliv = $curriculo[0]['numcredisoptliv'];

        # Disciplinas que o currículo exige
        $disciplinasObrigatorias = DisciplinasObrigatoria::where('id_crl', $curriculo[0]['id'])
                                                            ->orderBy('coddis', 'asc')
                                                            ->get(['coddis'])
                                                            ->toArray();
        $disciplinasOptativasEletivas = DisciplinasOptativasEletiva::where('id_crl', $curriculo[0]['id'])
                                                            ->orderBy('coddis', 'asc')
                                                            ->get(['coddis'])
                                                            ->toArray();
        $disciplinasLicenciaturas = DisciplinasLicenciatura::where('id_crl', $curriculo[0]['id'])
                                                            ->orderBy('coddis', 'asc')
                                                            ->get(['coddis'])
                                                            ->toArray();

        # Junção das disciplinas que o currículo exige
        $disciplinasJuntas = array_merge($disciplinasObrigatorias, $disciplinasOptativasEletivas, $disciplinasLicenciaturas);

        # Ordena as disciplinas que o currículo exige
        $disciplinasCurriculo = array();
        foreach ($disciplinasJuntas as $disciplinaCurriculo) {
            array_push($disciplinasCurriculo, $disciplinaCurriculo['coddis']);
        }
        sort($disciplinasCurriculo);

        # Disciplinas já concluidas
        $disciplinasConcluidas = Graduacao::disciplinasConcluidas($aluno, $this->repUnd);
        $disciplinasConcluidasCoddis = array();
        foreach ($disciplinasConcluidas as $disciplinaConcluida) {
            array_push($disciplinasConcluidasCoddis, $disciplinaConcluida['coddis']);
        }
        sort($disciplinasConcluidasCoddis);
      
        # Separa as disciplinas que faltam
        $disciplinasFaltam = array_diff($disciplinasCurriculo, $disciplinasConcluidasCoddis);

        # Disciplinas optativas livres 
        $disciplinasOptativasLivres = array_diff($disciplinasConcluidasCoddis, $disciplinasCurriculo);

        # Total de créditos de concluídos de Discplinas Optativas Livres
        $totnumcredisoptliv = 0;
        foreach ($disciplinasConcluidas as $disciplinaConcluida) {
            if (in_array($disciplinaConcluida['coddis'], $disciplinasOptativasLivres)) {
                $totnumcredisoptliv += $disciplinaConcluida['creaul'];
            }
        }

        # Programa
        $graduacaoPrograma = Graduacao::programa($aluno);        

        // return $arrSituacaoAluno;
    }
}
