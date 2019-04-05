<?php

namespace App\Http\Controllers;

use App\DisciplinasObrigatoriasEquivalente;
use App\DisciplinasObrigatoria;
use Illuminate\Http\Request;
use App\Curriculo;
use Auth;
use Uspdev\Replicado\Connection;
use Uspdev\Replicado\Graduacao;
use Carbon\Carbon;

class DisciplinasObrigatoriasEquivalenteController extends Controller
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
    public function create(DisciplinasObrigatoria $disciplinasObrigatoria)
    {
        $curriculo = Curriculo::find($disciplinasObrigatoria->id_crl);     
        $disciplinasObrigatoriasEquivalentes = DisciplinasObrigatoriasEquivalente::where('id_dis_obr', $disciplinasObrigatoria->id)->orderBy('coddis', 'asc')->get();
        $arrCoddis = config('ccg.arrCoddis');
        array_push($arrCoddis, $disciplinasObrigatoria->coddis);
        $disciplinas = Graduacao::obterDisciplinas($arrCoddis);  

        $disciplinasOferecidas = $disciplinas;
        foreach ($disciplinas as $key => $value) {
            if ($disciplinasObrigatoria['coddis'] == $value['coddis']) {
                unset($disciplinas[$key]);              
            }            
            foreach ($disciplinasObrigatoriasEquivalentes as $disciplinaObrigatoriaEquivalente) {
                if ($disciplinaObrigatoriaEquivalente['coddis'] == $value['coddis']) {
                    unset($disciplinas[$key]);              
                }
            }
        }

        return view('disciplinasObrEquivalentes.create', compact(
            'curriculo', 
            'disciplinas', 
            'disciplinasObrigatoria',
            'disciplinasObrigatoriasEquivalentes'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, DisciplinasObrigatoria $disciplinasObrigatoria)
    {
        $disciplinasObrigatoriasEquivalente = new DisciplinasObrigatoriasEquivalente;
        $disciplinasObrigatoriasEquivalente->id_dis_obr = $disciplinasObrigatoria->id;
        $disciplinasObrigatoriasEquivalente->coddis = $request->coddisobreqv;
        $disciplinasObrigatoriasEquivalente->tipeqv = $request->tipeqv;
        $disciplinasObrigatoriasEquivalente->save();

        $request->session()->flash('alert-success', 'Disciplina Obrigatória Equivalente cadastrada com sucesso!');
        return redirect("/disciplinasObrEquivalentes/create/" . $disciplinasObrigatoria->id);    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(DisciplinasObrigatoria $disciplinasObrigatoria)
    {
        $curriculo = Curriculo::find($disciplinasObrigatoria->id_crl);     
        $disciplinasObrigatoriasEquivalentes = DisciplinasObrigatoriasEquivalente::where('id_dis_obr', $disciplinasObrigatoria->id)->orderBy('coddis', 'asc')->get();

        return view('disciplinasObrEquivalentes.show', compact(
            'curriculo',
            'disciplinasObrigatoria', 
            'disciplinasObrigatoriasEquivalentes'
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
    public function destroy(DisciplinasObrigatoriasEquivalente $disciplinasObrEquivalente, Request $request)
    {
        $disciplinaObrigatoria = $disciplinasObrEquivalente->id_dis_obr;
        $disciplinasObrEquivalente->delete();
        $request->session()->flash('alert-danger', 'Disciplina Obrigatória Equivalente apagada!');
        return redirect("/disciplinasObrEquivalentes/" . $disciplinaObrigatoria);
    }
}
