<?php

namespace App\Http\Controllers;

use App\DisciplinasLicenciaturasEquivalente;
use App\DisciplinasLicenciatura;
use Illuminate\Http\Request;
use App\Curriculo;
use Auth;
use Uspdev\Replicado\Connection;
use Uspdev\Replicado\Graduacao;
use Carbon\Carbon;

class DisciplinasLicenciaturasEquivalenteController extends Controller
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
    public function create(DisciplinasLicenciatura $disciplinasLicenciatura)
    {
        $curriculo = Curriculo::find($disciplinasLicenciatura->id_crl);     
        $disciplinasLicenciaturasEquivalentes = DisciplinasLicenciaturasEquivalente::where('id_dis_lic', $disciplinasLicenciatura->id)->get();
        $arrCoddis = config('app.arrCoddis');
        array_push($arrCoddis, $disciplinasLicenciatura->coddis);
        $disciplinas = Graduacao::obterDisciplinas($arrCoddis);  

        $disciplinasOferecidas = $disciplinas;
        foreach ($disciplinas as $key => $value) {
            if ($disciplinasLicenciatura['coddis'] == $value['coddis']) {
                unset($disciplinas[$key]);              
            }            
            foreach ($disciplinasLicenciaturasEquivalentes as $disciplinaLicenciaturaEquivalente) {
                if ($disciplinaLicenciaturaEquivalente['coddis'] == $value['coddis']) {
                    unset($disciplinas[$key]);              
                }
            }
        }

        return view('disciplinasLicEquivalentes.create', compact(
            'curriculo', 
            'disciplinas', 
            'disciplinasLicenciatura',
            'disciplinasLicenciaturasEquivalentes'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, DisciplinasLicenciatura $disciplinasLicenciatura)
    {
        $disciplinasLicenciaturasEquivalente = new DisciplinasLicenciaturasEquivalente;
        $disciplinasLicenciaturasEquivalente->id_dis_lic = $disciplinasLicenciatura->id;
        $disciplinasLicenciaturasEquivalente->coddis = $request->coddisliceqv;
        $disciplinasLicenciaturasEquivalente->save();

        $request->session()->flash('alert-success', 'Disciplina Licenciatura Equivalente cadastrada com sucesso!');
        return redirect("/disciplinasLicEquivalentes/create/" . $disciplinasLicenciatura->id);    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(DisciplinasLicenciatura $disciplinasLicenciatura)
    {
        $curriculo = Curriculo::find($disciplinasLicenciatura->id_crl);     
        $disciplinasLicenciaturasEquivalentes = DisciplinasLicenciaturasEquivalente::where('id_dis_lic', $disciplinasLicenciatura->id)->get();

        return view('disciplinasLicEquivalentes.show', compact(
            'curriculo',
            'disciplinasLicenciatura', 
            'disciplinasLicenciaturasEquivalentes'
        ));
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
    public function destroy(DisciplinasLicenciaturasEquivalente $disciplinasLicEquivalente, Request $request)
    {
        $disciplinaLicenciatura = $disciplinasLicEquivalente->id_dis_lic;
        $disciplinasLicEquivalente->delete();
        $request->session()->flash('alert-danger', 'Disciplina Licenciatura Equivalente apagada!');
        return redirect("/disciplinasLicEquivalentes/" . $disciplinaLicenciatura);
    }
}
