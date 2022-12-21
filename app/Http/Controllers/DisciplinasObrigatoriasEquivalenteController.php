<?php

namespace App\Http\Controllers;

use App\Models\Curriculo;
use App\Models\DisciplinasObrigatoria;
use App\Models\DisciplinasObrigatoriasEquivalente;
use Illuminate\Http\Request;
use Uspdev\Replicado\Graduacao;

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
        $disciplinaObrigatoriaEquivalente = DisciplinasObrigatoriasEquivalente::where('id', $id)->get();
        $disciplinaObrigatoria = DisciplinasObrigatoria::where('id', $disciplinaObrigatoriaEquivalente[0]->id_dis_obr)->get();
        $curriculo = Curriculo::find($disciplinaObrigatoria[0]->id_crl);

        return view('disciplinasObrEquivalentes.edit', compact(
            'curriculo',
            'disciplinaObrigatoria',
            'disciplinaObrigatoriaEquivalente'
        ));
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
        $disciplinasObrigatoriasEquivalente = DisciplinasObrigatoriasEquivalente::find($id);
        $disciplinasObrigatoriasEquivalente->id_dis_obr = $request->id_dis_obr;
        $disciplinasObrigatoriasEquivalente->coddis = $request->coddis;
        $disciplinasObrigatoriasEquivalente->tipeqv = $request->tipeqv;
        $disciplinasObrigatoriasEquivalente->save();

        $request->session()->flash('alert-success', 'Disciplina Obrigatória Equivalente salva com sucesso!');
        return redirect("/disciplinasObrEquivalentes/" . $request->id_dis_obr);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  Array $disciplinasEquivalentes
     * @param  Curriculo $curriculo
     */
    public static function storeEquivalenteJupiter(array $disciplinasEquivalentes, Curriculo $curriculo)
    {
        if (isset($disciplinasEquivalentes) && !is_null($disciplinasEquivalentes)) {
            foreach ($disciplinasEquivalentes as $disciplina => $equivalencias) {
                // Utilizando substr() pois o coddis está acompanhado de _tipobg (_O)
                $disciplinaObrigatoria = DisciplinasObrigatoria::select('id')
                    ->where('coddis', '=', substr($disciplina, 0, -2))
                    ->where('id_crl', '=', $curriculo->id)
                    ->get();
                // Verifica se a disciplina obrigatória faz parte do curriculo no CCG
                if (isset($disciplinaObrigatoria) && !is_null($disciplinaObrigatoria)) {
                    // Percorre todas as equivalências 'OU'/'E'
                    foreach ($equivalencias as $tipo => $coddis_eq) {
                        switch ($tipo) {
                            // Quando é do tipo 'OU', pode haver UMA
                            case 'OU':
                                foreach ($coddis_eq as $disciplina_equivalente) {
                                    $disciplinasObrigatoriasEquivalente = new DisciplinasObrigatoriasEquivalente;
                                    // $disciplinasObrigatoriasEquivalente->id_dis_obr = $disciplinaObrigatoria[0]->id;
                                    $disciplinasObrigatoriasEquivalente->id_dis_obr = empty($disciplinaObrigatoria[0]) ? 'sem id': $disciplinaObrigatoria[0]->id;
                                    $disciplinasObrigatoriasEquivalente->coddis = $disciplina_equivalente[0];
                                    $disciplinasObrigatoriasEquivalente->tipeqv = 'OU';
                                    try {
                                        $disciplinasObrigatoriasEquivalente->save();
                                    } catch (\Exception $e) {
                                        echo 'Não foi possível salvar as equivalências!';
                                    }
                                }
                                break;

                            // Quando é do tipo 'E', haverá MAIS de uma
                            case 'E':
                                foreach ($coddis_eq as $disciplina_equivalente) {
                                    $disciplinasObrigatoriasEquivalente = new DisciplinasObrigatoriasEquivalente;
                                    $disciplinasObrigatoriasEquivalente->id_dis_obr = empty($disciplinaObrigatoria[0]) ? 'sem id': $disciplinaObrigatoria[0]->id;
                                    $disciplinasObrigatoriasEquivalente->coddis = $disciplina_equivalente;
                                    $disciplinasObrigatoriasEquivalente->tipeqv = 'E';
                                    try {
                                        $disciplinasObrigatoriasEquivalente->save();
                                    } catch (\Exception $e) {
                                        echo 'Não foi possível salvar as equivalências!';
                                    }
                                }
                                break;
                        }
                    }
                }
            }
        }
    }
}
