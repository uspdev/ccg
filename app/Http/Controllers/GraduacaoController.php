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
        
        $this->repIp    = env('REPLICADO_HOST');
        $this->repPort  = env('REPLICADO_PORT');
        $this->repDb    = env('REPLICADO_DATABASE');
        $this->repUser  = env('REPLICADO_USERNAME');
        $this->repPass  = env('REPLICADO_PASSWORD');
        $this->repUnd   = env('REPLICADO_CODUND');
        $this->repSgbd  = env('REPLICADO_SGBD');
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
 
        $graduacaoCurso = $graduacao->curso(env('CODPES_ALUNO'), $this->repUnd); #desenvolvimento
        # $graduacaoCurso = $graduacao->curso(Auth::user()->id, $this->repUnd); #produção
        
        return view('aluno.creditos', compact('graduacaoCurso'));
    }
}
