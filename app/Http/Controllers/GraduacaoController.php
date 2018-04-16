<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Uspdev\Replicado\Connection;
use Uspdev\Replicado\Pessoa;
use Uspdev\Replicado\Graduacao;
use Carbon;

class GraduacaoController extends Controller
{
    public function __construct()
    {
        $this->repIp   = env('REPLICADO_HOST');
        $this->repPort = env('REPLICADO_PORT');
        $this->repDb   = env('REPLICADO_DATABASE');
        $this->repUser = env('REPLICADO_USERNAME');
        $this->repPass = env('REPLICADO_PASSWORD');
        $this->codund = env('REPLICADO_CODUND');
        $this->sgbd = env('REPLICADO_SGBD');
    }
    
    public function busca()
    {
        return view('graduacao.busca');
    }
    
    public function buscaReplicado(Request $request)
    {
        
        $replicado = new Connection($this->repIp, $this->repPort, $this->repDb, $this->repUser, $this->repPass);
        $this->sgbd == 'sybase' ? $replicado->setSybase() : $replicado->setMssql();
        
        $pessoa = new Pessoa($replicado->conn);
        $pessoaDump = $pessoa->dump($request->codpes);

        $graduacao = new Graduacao($replicado->conn);
        
        // É aluno de graduação ATIVO da unidade? 
        if ($graduacao->verifica($request->codpes, env('REPLICADO_CODUND'))) {
            // Retorna os dados acadêmicos
            $graduacaoCurso = $graduacao->curso($request->codpes, env('REPLICADO_CODUND'));
        } else {
            $request->session()->flash('alert-danger',"Não é aluno de graduação da {$this->codund}");
            return redirect('/busca');
        }

        return view('graduacao.busca', compact('graduacaoCurso'));
    }
}
