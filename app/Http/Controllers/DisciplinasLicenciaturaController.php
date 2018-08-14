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

class DisciplinasLicenciaturaController extends Controller
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
        
        $arrCoddis = config('app.arrCoddis');
        $disciplinas = Graduacao::obterDisciplinas($arrCoddis);  

        foreach ($disciplinas as $key => $value) {
            foreach ($disciplinasLicenciaturas as $disciplinaLicenciatura) {
                if ($disciplinaLicenciatura['coddis'] === $value['coddis']) {
                    unset($disciplinas[$key]);                   
                }
            }
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
        }

        return view('disciplinasLicenciaturas.create', compact(
            'curriculo', 
            'disciplinas', 
            'disciplinasLicenciaturas'
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
        $disciplinasLicenciatura = new DisciplinasLicenciatura;
        $disciplinasLicenciatura->id_crl = $curriculo->id;
        $disciplinasLicenciatura->coddis = $request->coddisobr;
        $disciplinasLicenciatura->save();

        $request->session()->flash('alert-success', 'Disciplina Licenciatura cadastrada com sucesso!');
        return redirect("/disciplinasLicenciaturas/create/" . $curriculo->id);
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
    public function destroy(DisciplinasLicenciatura $disciplinasLicenciatura, Request $request)
    {
        $disciplinasLicenciatura->delete();
        $request->session()->flash('alert-danger', 'Disciplina Licenciatura apagada!');
        return redirect("/curriculos/" . $disciplinasLicenciatura['id_crl']);
    }
}
