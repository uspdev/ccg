<?php

namespace App\Http\Controllers;

use App\DisciplinasObrigatoria;
use App\DisciplinasOptativasEletiva;
use App\DisciplinasLicenciatura;
use Illuminate\Http\Request;
use App\Curriculo;
use Auth;
use Uspdev\Replicado\Connection;
use Uspdev\Replicado\Graduacao;
use Carbon\Carbon;

class DisciplinasObrigatoriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Curriculo $curriculo)
    {      
        $disciplinasObrigatorias = DisciplinasObrigatoria::where('id_crl', $curriculo->id)->orderBy('coddis', 'asc')->get();
        $disciplinasOptativasEletivas = DisciplinasOptativasEletiva::where('id_crl', $curriculo->id)->orderBy('coddis', 'asc')->get();
        $disciplinasLicenciaturas = DisciplinasLicenciatura::where('id_crl', $curriculo->id)->orderBy('coddis', 'asc')->get();
        
        $arrCoddis = config('ccg.arrCoddis');
        $disciplinas = Graduacao::obterDisciplinas($arrCoddis);  

        foreach ($disciplinas as $key => $value) {
            foreach ($disciplinasObrigatorias as $disciplinaObrigatoria) {
                if ($disciplinaObrigatoria['coddis'] === $value['coddis']) {
                    unset($disciplinas[$key]);                   
                }
            }
            foreach ($disciplinasOptativasEletivas as $disciplinaOptativaEletiva) {
                if ($disciplinaOptativaEletiva['coddis'] === $value['coddis']) {
                    unset($disciplinas[$key]);                   
                }
            }            
            foreach ($disciplinasLicenciaturas as $disciplinaLicenciatura) {
                if ($disciplinaLicenciatura['coddis'] === $value['coddis']) {
                    unset($disciplinas[$key]);                   
                }
            }            
        }

        return view('disciplinasObrigatorias.create', compact(
            'curriculo', 
            'disciplinas', 
            'disciplinasObrigatorias'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Curriculo $curriculo)
    {
        $disciplinasObrigatoria = new DisciplinasObrigatoria;
        $disciplinasObrigatoria->id_crl = $curriculo->id;
        $disciplinasObrigatoria->coddis = $request->coddisobr;
        $disciplinasObrigatoria->save();

        $request->session()->flash('alert-success', 'Disciplina Obrigatória cadastrada com sucesso!');
        return redirect("/disciplinasObrigatorias/create/" . $curriculo->id);
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
    public function destroy(DisciplinasObrigatoria $disciplinasObrigatoria, Request $request)
    {
        $curriculo = $disciplinasObrigatoria->id_crl;
        $disciplinasObrigatoria->delete();
        $request->session()->flash('alert-danger', 'Disciplina Obrigatória apagada!');
        
        return redirect("/curriculos/" . $curriculo);
    }
}
