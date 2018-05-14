<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Uspdev\Replicado\Connection;
use Uspdev\Replicado\Graduacao;
use Carbon;

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
            $graduacaoCurso = Graduacao::curso($request->codpes, $this->repUnd);
        } else {
            $msg = "O nº USP $request->codpes não pertence a um aluno ativo de Graduação nesta unidade."; 
            $request->session()->flash('alert-danger', $msg);
            return redirect('/busca');
        }

        return view('graduacao.busca', compact('graduacaoCurso'));
    }

    public function creditos()
    {
        $gate = $this->getGate();
        if ($gate === 'secretaria') {
            $graduacaoCurso = Graduacao::curso(env('CODPES_ALUNO'), $this->repUnd); #desenvolvimento
        } else {
            $graduacaoCurso = Graduacao::curso(Auth::user()->id, $this->repUnd); #produção
        }
        
        return view('aluno.creditos', compact('graduacaoCurso', 'gate'));
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
        $strFiltro = "AND PESSOA.nompes LIKE '%$parteNome%'";
        $alunos = Graduacao::ativos($this->repUnd, $strFiltro);
        return response($alunos);
    }
}
