<?php

namespace App\Http\Controllers;

use App\Models\AlunosObservacoes;
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
        # Somente um registro, pk composta por id_crl e codpes
        # Se existe o registro
        $observacoes = AlunosObservacoes::where(['id_crl' => $request->id_crl, 'codpes' => $request->codpes])->first();
        # Se existe o registro
        if (!empty($observacoes)) {
            # Se existe e $request->txtobs está vazio
            if (empty(trim($request->txtobs))) {
                #delete
                $observacoes->delete();
            # Se existe e $request->txtobs foi alterado
            } elseif ($observacoes->txtobs != $request->txtobs) {
                # update
                $observacoes->id_crl = $request->id_crl;
                $observacoes->codpes = $request->codpes;
                $observacoes->txtobs = $request->txtobs;
                $observacoes->save();                
            }
        # Se não existe
        } else {
            # store
            if (!empty(trim($request->txtobs))) {
                $observacoes = new AlunosObservacoes;
                $observacoes->id_crl = $request->id_crl;
                $observacoes->codpes = $request->codpes;
                $observacoes->txtobs = $request->txtobs;
                $observacoes->save();
            }
        }

        return redirect("/creditos/{$request->codpes}/pdf");    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AlunosObservacoes  $alunosObservacoes
     * @return \Illuminate\Http\Response
     */
    public function show(AlunosObservacoes $alunosObservacoes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AlunosObservacoes  $alunosObservacoes
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
     * @param  \App\Models\AlunosObservacoes  $alunosObservacoes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AlunosObservacoes $alunosObservacoes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AlunosObservacoes  $alunosObservacoes
     * @return \Illuminate\Http\Response
     */
    public function destroy(AlunosObservacoes $alunosObservacoes)
    {
        //
    }
}
