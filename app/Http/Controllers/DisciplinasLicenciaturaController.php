<?php

namespace App\Http\Controllers;

use App\Models\DisciplinasObrigatoria;
use App\Models\DisciplinasOptativasEletiva;
use App\Models\DisciplinasLicenciatura;
use Illuminate\Http\Request;
use App\Models\Curriculo;
use Auth;
use Uspdev\Replicado\Connection;
use Uspdev\Replicado\Graduacao;
use Carbon\Carbon;

class DisciplinasLicenciaturaController extends Controller
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

        $disciplinas = Graduacao::obterDisciplinas(config('ccg.arrCoddis'));  

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
        # verifica se preencheu campo com várias disciplinas
        if (!$request->coddislic) {
            if (!$request->lstcoddis) {
                $request->session()->flash('alert-danger', 'Selecione uma disciplina ou preencha a lista de disciplinas!');
                return redirect("/disciplinasLicenciaturas/create/" . $curriculo->id);
            } else {
                # salva várias diciplinas
                $lstcoddis = rtrim(ltrim(str_replace(' ', '', strtoupper($request->lstcoddis))));
                $lstcoddis = preg_replace('/\r|\n/', '', $lstcoddis);
                $arrlstcoddis = explode(',', $lstcoddis);
                $arrlstcoddis = array_filter($arrlstcoddis);
                $disciplinas = Graduacao::obterDisciplinas(config('ccg.arrCoddis'));
                $arrdis = Array();
                foreach ($disciplinas as $disciplina) {
                    array_push($arrdis, $disciplina['coddis']);
                }
                foreach ($arrlstcoddis as $coddis) {
                    # verifica se a disciplina existe para a unidade
                    if (in_array($coddis, $arrdis)) {
                        # verifica se a disciplina não está salva
                        if (DisciplinasLicenciatura::where(['id_crl' => $curriculo->id, 'coddis' => $coddis])->count() == 0) {
                            # salva a disciplina
                            $disciplinasObrigatoria = new DisciplinasLicenciatura;
                            $disciplinasObrigatoria->id_crl = $curriculo->id;
                            $disciplinasObrigatoria->coddis = $coddis;
                            $disciplinasObrigatoria->save();
                        } 
                    } 
                }  
            }
        } else {
            # salva uma disciplina
            $disciplinasObrigatoria = new DisciplinasLicenciatura;
            $disciplinasObrigatoria->id_crl = $curriculo->id;
            $disciplinasObrigatoria->coddis = $request->coddislic;
            $disciplinasObrigatoria->save();
        }
        $request->session()->flash('alert-success', 'Disciplina(s) Licenciatura(s) cadastrada(s) com sucesso!');
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
