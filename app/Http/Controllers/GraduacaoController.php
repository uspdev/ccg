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
    }
    
    public function busca()
    {
        return view('graduacao.busca');
    }
    
    public function buscaReplicado(Request $request)
    {
        
        $replicado = new Connection($this->repIp, $this->repPort, $this->repDb, $this->repUser, $this->repPass);
        $this->repSgbd == 'sybase' ? $replicado->setSybase() : $replicado->setMssql();
        
        $graduacao = new Graduacao($replicado->conn);

        // É aluno de graduação ATIVO da unidade? 
        if ($graduacao->verifica($request->codpes, $this->repUnd)) {
            // Retorna os dados acadêmicos
            $graduacaoCurso = $graduacao->curso($request->codpes, $this->repUnd);
        } else {
            $msg = "O nº USP $request->codpes não pertence a um aluno ativo de Graduação nesta unidade."; 
            $request->session()->flash('alert-danger', $msg);
            return redirect('/busca');
        }

        return view('graduacao.busca', compact('graduacaoCurso'));
    }

    public function creditos()
    {
        $replicado = new Connection($this->repIp, $this->repPort, $this->repDb, $this->repUser, $this->repPass);
        $this->repSgbd == 'sybase' ? $replicado->setSybase() : $replicado->setMssql();
        
        $graduacao = new Graduacao($replicado->conn);
 
        $gate = $this->getGate();
        if ($gate === 'secretaria') {
            $graduacaoCurso = $graduacao->curso(env('CODPES_ALUNO'), $this->repUnd); #desenvolvimento
        } else {
            $graduacaoCurso = $graduacao->curso(Auth::user()->id, $this->repUnd); #produção
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
        
        $replicado = new Connection($this->repIp, $this->repPort, $this->repDb, $this->repUser, $this->repPass);
        $this->repSgbd == 'sybase' ? $replicado->setSybase() : $replicado->setMssql();

        $graduacao = new Graduacao($replicado->conn);
        
        $alunos = $graduacao->ativos($this->repUnd, $strFiltro);
        
        return response($alunos);
    }
}
