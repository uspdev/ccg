<?php

namespace App\Http\Controllers;

use App\Curriculo;
use App\DisciplinasObrigatoria;
use App\DisciplinasOptativasEletiva;
use App\DisciplinasLicenciatura;
use Illuminate\Http\Request;
use Auth;
use Uspdev\Replicado\Connection;
use Uspdev\Replicado\Graduacao;
use Carbon\Carbon;

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

        return view('curriculos.create', compact('cursos', 'habilitacoes', 'cursosHabilitacoes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $curriculo = new Curriculo;
        $curriculo->codcur = $request->codcur;
        $curriculo->codhab = $request->codhab;
        $curriculo->numcredisoptelt = $request->numcredisoptelt;
        $curriculo->numcredisoptliv = $request->numcredisoptliv;
        $curriculo->dtainicrl = Carbon::parse($request->dtainicrl);
        $curriculo->save();

        $request->session()->flash('alert-success', 'Curriculo cadastrado com sucesso!');
        return redirect('/curriculos');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Curriculo $curriculo)
    {
        $disciplinasObrigatorias = DisciplinasObrigatoria::where('id_crl', $curriculo->id)->get();
        $disciplinasOptativasEletivas = DisciplinasOptativasEletiva::where('id_crl', $curriculo->id)->get();
        $disciplinasLicenciaturas = DisciplinasLicenciatura::where('id_crl', $curriculo->id)->get();
        
        return view('curriculos.show', compact(
            'curriculo', 
            'disciplinasObrigatorias', 
            'disciplinasOptativasEletivas',
            'disciplinasLicenciaturas'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Curriculo $curriculo)
    {
        $disciplinasObrigatorias = DisciplinasObrigatoria::where('id_crl', $curriculo->id)->get();
        $disciplinasOptativasEletivas = DisciplinasOptativasEletiva::where('id_crl', $curriculo->id)->get();
        $disciplinasLicenciaturas = DisciplinasLicenciatura::where('id_crl', $curriculo->id)->get();        
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

        return view('curriculos.edit', compact(
            'curriculo', 
            'cursos', 
            'habilitacoes', 
            'cursosHabilitacoes',
            'disciplinasObrigatorias',
            'disciplinasOptativasEletivas',
            'disciplinasLicenciaturas'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Curriculo $curriculo)
    {
        $curriculo->codcur = $request->codcur;
        $curriculo->codhab = $request->codhab;
        $curriculo->numcredisoptelt = $request->numcredisoptelt;
        $curriculo->numcredisoptliv = $request->numcredisoptliv;
        $curriculo->dtainicrl = Carbon::parse($request->dtainicrl);
        $curriculo->save();

        $request->session()->flash('alert-success', 'Curriculo salvo com sucesso!');
        return redirect("/curriculos/$curriculo->id");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Curriculo $curriculo, Request $request)
    {
        $curriculo->delete();
        $request->session()->flash('alert-danger', 'Curriculo apagado!');
        return redirect('/curriculos');
    }
}
