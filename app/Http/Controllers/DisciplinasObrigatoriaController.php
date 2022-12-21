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
        
        $disciplinas = Graduacao::obterDisciplinas(config('ccg.arrCoddis'));  

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
        # verifica se preencheu campo com várias disciplinas
        if (!$request->coddisobr) {
            if (!$request->lstcoddis) {
                $request->session()->flash('alert-danger', 'Selecione uma disciplina ou preencha a lista de disciplinas!');
                return redirect("/disciplinasObrigatorias/create/" . $curriculo->id);
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
                        if (DisciplinasObrigatoria::where(['id_crl' => $curriculo->id, 'coddis' => $coddis])->count() == 0) {
                            # salva a disciplina
                            $disciplinasObrigatoria = new DisciplinasObrigatoria;
                            $disciplinasObrigatoria->id_crl = $curriculo->id;
                            $disciplinasObrigatoria->coddis = $coddis;
                            $disciplinasObrigatoria->save();
                        } 
                    } 
                }  
            }
        } else {
            # salva uma disciplina
            $disciplinasObrigatoria = new DisciplinasObrigatoria;
            $disciplinasObrigatoria->id_crl = $curriculo->id;
            $disciplinasObrigatoria->coddis = $request->coddisobr;
            $disciplinasObrigatoria->save();
        }
        $request->session()->flash('alert-success', 'Disciplina(s) Obrigatória(s) cadastrada(s) com sucesso!');
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

    /**
     * Método estático para salvar disciplinas obrigatórias
     * a partir da grade curricular atual do JupiterWeb
     * 
     * @param Array $disciplinasObrigatorias
     * @param Curriculo $curriculo
     */
    public static function storeJupiter($disciplinasObrigatorias, Curriculo $curriculo)
    {
        if (!is_null($disciplinasObrigatorias)) {
            # salva várias diciplinas
            $arrlstcoddis = array_filter($disciplinasObrigatorias);
            // dd($arrlstcoddis, $disciplinasObrigatorias);
            $disciplinas = Graduacao::obterDisciplinas(config('ccg.arrCoddis'));
            $arrdis = array();
            foreach ($disciplinas as $disciplina) {
                array_push($arrdis, $disciplina['coddis']);
            }
            foreach ($arrlstcoddis as $coddis) {
                # verifica se a disciplina existe para a unidade
                # Aqui acredito que deveria ter todas as disciplinas da grade curricular e não
                # somente os da unidade. Deixei comentado o if. Masaki 12/22
                // if (in_array($coddis, $arrdis)) {
                    # verifica se a disciplina não está salva
                    if (DisciplinasObrigatoria::where(['id_crl' => $curriculo->id, 'coddis' => $coddis])->count() == 0) {
                        # salva a disciplina
                        $disciplinaObrigatoria = new DisciplinasObrigatoria;
                        $disciplinaObrigatoria->id_crl = $curriculo->id;
                        $disciplinaObrigatoria->coddis = $coddis;
                        $disciplinaObrigatoria->save();
                    }
                // }
            }
        } 
    }
}
