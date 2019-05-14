<?php

namespace App\Http\Controllers;

use App\AlunosObservacoes;
use Illuminate\Http\Request;

class AlunosObservacoesController extends Controller
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
        if (!empty(trim($request->txtobs))) {
            $observacoes = new AlunosObservacoes;
            $observacoes->id_crl = $request->id_crl;
            $observacoes->codpes = $request->codpes;
            $observacoes->txtobs = $request->txtobs;
            $observacoes->save();
            $request->session()->flash('alert-success', 'Observações salvas com sucesso!');
        }

        return redirect("/creditos/{$request->codpes}/pdf");    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AlunosObservacoes  $alunosObservacoes
     * @return \Illuminate\Http\Response
     */
    public function show(AlunosObservacoes $alunosObservacoes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AlunosObservacoes  $alunosObservacoes
     * @return \Illuminate\Http\Response
     */
    public function edit(AlunosObservacoes $alunosObservacoes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AlunosObservacoes  $alunosObservacoes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AlunosObservacoes $alunosObservacoes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AlunosObservacoes  $alunosObservacoes
     * @return \Illuminate\Http\Response
     */
    public function destroy(AlunosObservacoes $alunosObservacoes)
    {
        //
    }
}
