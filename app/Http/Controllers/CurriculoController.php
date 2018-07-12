<?php

namespace App\Http\Controllers;

use App\Curriculo;
use Illuminate\Http\Request;
use Auth;
use Uspdev\Replicado\Connection;
use Uspdev\Replicado\Graduacao;

class CurriculoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->repUnd = env('REPLICADO_CODUND');
    }
        
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $curriculos = Curriculo::all();
        
        return view('curriculos.index', compact('curriculos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cursosHabilitacoes = Graduacao::obterCursosHabilitacoes($this->repUnd);
        
        $cursos = array();
        foreach ($cursosHabilitacoes as $curso) {
            if (!in_array(array('codcur' => $curso['codcur'], 'nomcur' => $curso['nomcur']), $cursos)) {
                array_push($cursos, array('codcur' => $curso['codcur'], 'nomcur' => $curso['nomcur']));
            }
        }

        $habilitacoes = array();
        foreach ($cursosHabilitacoes as $habilitacao) {
            if (!in_array(array('codhab' => $habilitacao['codhab'], 'nomhab' => $habilitacao['nomhab']), $habilitacoes)) {
                array_push($habilitacoes, array('codhab' => $habilitacao['codhab'], 'nomhab' => $habilitacao['nomhab']));
            }    
        }

        return view('curriculos.create', compact('cursos', 'habilitacoes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
