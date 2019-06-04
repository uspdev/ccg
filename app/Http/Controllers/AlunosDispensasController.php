<?php

namespace App\Http\Controllers;

use App\AlunosDispensas;
use Illuminate\Http\Request;

class AlunosDispensasController extends Controller
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        # Se alguma disciplina foi selecionada
        if (isset($request->coddis)) {
            # Somente registro id_crl e codpes
            # Se existe registro, deleta
            $delete = AlunosDispensas::where(['id_crl' => $request->id_crl, 'codpes' => $request->codpes])->delete();
            # Salva as dispensas selecionadas
            $dispensas = new AlunosDispensas;
            $dispensas->id_crl = $request->id_crl;
            $dispensas->codpes = $request->codpes;
            $dispensas->coddis = implode(',', $request->coddis); 
            $dispensas->save();
        # Se nenhuma disciplina foi selecionada, delete 
        } else {
            $delete = AlunosDispensas::where(['id_crl' => $request->id_crl, 'codpes' => $request->codpes])->delete();
        }

        # Recalcula os créditos
        return redirect("/creditos/{$request->codpes}");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AlunosDispensas  $alunosDispensas
     * @return \Illuminate\Http\Response
     */
    public function show(AlunosDispensas $alunosDispensas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AlunosDispensas  $alunosDispensas
     * @return \Illuminate\Http\Response
     */
    public function edit(AlunosDispensas $alunosDispensas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AlunosDispensas  $alunosDispensas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AlunosDispensas $alunosDispensas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AlunosDispensas  $alunosDispensas
     * @return \Illuminate\Http\Response
     */
    public function destroy(AlunosDispensas $alunosDispensas)
    {
        //
    }
}
