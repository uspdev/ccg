<?php

namespace App\Http\Controllers;

use App\Curriculo;
use App\DisciplinasObrigatoria;
use App\DisciplinasObrigatoriasEquivalente;
use App\DisciplinasOptativasEletiva;
use App\DisciplinasLicenciatura;
use App\DisciplinasLicenciaturasEquivalente;
use Illuminate\Http\Request;
use Auth;
use Uspdev\Replicado\Connection;
use Uspdev\Replicado\Graduacao;
use Carbon\Carbon;
use App\Ccg\Aluno;

class CurriculoController extends Controller
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
        // $curriculos = Curriculo::all();

        # Descobrir qual é o ano mais recente com Currículo cadastrado, do contrário considera o ano atual
        $anos = Curriculo::select('dtainicrl')
            ->groupBy('dtainicrl')
            ->orderBy('dtainicrl', 'desc')
            ->get();

        # Get ano
        if (isset(explode('=', url()->full())[1])) {
            if (explode('=', url()->full())[1] == 'Tudo') {
                $ano = explode('=', url()->full())[1];
            } else {
                $ano = explode('=', url()->full())[1] . '-01-01';
            }
        } else {
            $ano = Curriculo::select('dtainicrl')
                ->groupBy('dtainicrl')
                ->orderBy('dtainicrl', 'desc')
                ->first()
                ->dtainicrl ?? Carbon::now()->format('Y') . '-01-01';
        }

        # Verifica se são todos os anos
        if ($ano == 'Tudo') {
            $curriculos = Curriculo::all();
        } else {
            $curriculos = Curriculo::where('dtainicrl', $ano)->get();
        }        

        return view('curriculos.index', compact('curriculos', 'ano', 'anos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cursosHabilitacoes = Graduacao::obterCursosHabilitacoes(config('ccg.codUnd'));
        
        // Ordena por curso em ordem crescente
        array_multisort(array_column($cursosHabilitacoes, "codcur"), SORT_ASC, $cursosHabilitacoes);

        $cursos = array();
        foreach ($cursosHabilitacoes as $curso) {
            if (!in_array(array('codcur' => $curso['codcur'], 'nomcur' => $curso['nomcur']), $cursos)) {
                array_push($cursos, array('codcur' => $curso['codcur'], 'nomcur' => $curso['nomcur']));
            }
        }  
        
        $habilitacoes = array();
        foreach ($cursosHabilitacoes as $habilitacao) {
            if (!in_array(array('codhab' => $habilitacao['codhab'], 'nomhab' => $habilitacao['nomhab']), $habilitacoes)) {
                array_push($habilitacoes, array('codhab' => $habilitacao['codhab'], 'nomhab' => $habilitacao['nomhab']));
            }    
        }

        return view('curriculos.create', compact('cursos', 'habilitacoes', 'cursosHabilitacoes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $curriculo = new Curriculo;
        $curriculo->codcur = $request->codcur;
        $curriculo->codhab = $request->codhab;
        $curriculo->numcredisoptelt = $request->numcredisoptelt;
        $curriculo->numcredisoptliv = $request->numcredisoptliv;
        $curriculo->dtainicrl = Carbon::parse($request->dtainicrl);
        $curriculo->txtobs = $request->txtobs;
        $curriculo->numtotcredisoptelt = $request->numtotcredisoptelt;
        $curriculo->numtotcredisoptliv = $request->numtotcredisoptliv;        
        $curriculo->save();

        $request->session()->flash('alert-success', 'Curriculo cadastrado com sucesso!');
        return redirect('/curriculos');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Curriculo $curriculo)
    {
        $disciplinasObrigatorias = DisciplinasObrigatoria::where('id_crl', $curriculo->id)->orderBy('coddis', 'asc')->get();
        $disciplinasOptativasEletivas = DisciplinasOptativasEletiva::where('id_crl', $curriculo->id)->orderBy('coddis', 'asc')->get();
        $disciplinasLicenciaturas = DisciplinasLicenciatura::where('id_crl', $curriculo->id)->orderBy('coddis', 'asc')->get();
        
        return view('curriculos.show', compact(
            'curriculo', 
            'disciplinasObrigatorias', 
            'disciplinasOptativasEletivas',
            'disciplinasLicenciaturas'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Curriculo $curriculo)
    {
        $disciplinasObrigatorias = DisciplinasObrigatoria::where('id_crl', $curriculo->id)->orderBy('coddis', 'asc')->get();
        $disciplinasOptativasEletivas = DisciplinasOptativasEletiva::where('id_crl', $curriculo->id)->orderBy('coddis', 'asc')->get();
        $disciplinasLicenciaturas = DisciplinasLicenciatura::where('id_crl', $curriculo->id)->orderBy('coddis', 'asc')->get();        
        $cursosHabilitacoes = Graduacao::obterCursosHabilitacoes(config('ccg.codUnd'));
        
        // Ordena por curso em ordem crescente
        array_multisort(array_column($cursosHabilitacoes, "codcur"), SORT_ASC, $cursosHabilitacoes);
        
        $cursos = array();
        foreach ($cursosHabilitacoes as $curso) {
            if (!in_array(array('codcur' => $curso['codcur'], 'nomcur' => $curso['nomcur']), $cursos)) {
                array_push($cursos, array('codcur' => $curso['codcur'], 'nomcur' => $curso['nomcur']));
            }
        }

        $habilitacoes = array();
        foreach ($cursosHabilitacoes as $habilitacao) {
            if (!in_array(array('codhab' => $habilitacao['codhab'], 'nomhab' => $habilitacao['nomhab']), $habilitacoes)) {
                array_push($habilitacoes, array('codhab' => $habilitacao['codhab'], 'nomhab' => $habilitacao['nomhab']));
            }    
        }

        return view('curriculos.edit', compact(
            'curriculo', 
            'cursos', 
            'habilitacoes', 
            'cursosHabilitacoes',
            'disciplinasObrigatorias',
            'disciplinasOptativasEletivas',
            'disciplinasLicenciaturas'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Curriculo $curriculo)
    {
        $curriculo->codcur = $request->codcur;
        $curriculo->codhab = $request->codhab;
        $curriculo->numcredisoptelt = $request->numcredisoptelt;
        $curriculo->numcredisoptliv = $request->numcredisoptliv;
        $curriculo->dtainicrl = Carbon::parse($request->dtainicrl);
        $curriculo->txtobs = $request->txtobs;
        $curriculo->numtotcredisoptelt = $request->numtotcredisoptelt;
        $curriculo->numtotcredisoptliv = $request->numtotcredisoptliv;
        $curriculo->save();

        $request->session()->flash('alert-success', 'Curriculo salvo com sucesso!');
        return redirect("/curriculos/$curriculo->id");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id                
     * @return \Illuminate\Http\Response                
     */
    public function destroy(Curriculo $curriculo, Request $request)
    {   
        $curriculo->delete();
        $request->session()->flash('alert-danger', 'Curriculo apagado!');
        return redirect('/curriculos');
    }

    /**
     * Lista os alunos que pertencem ao Currículo
     *
     * @param  int  $id
     * @return array  $alunosCurriculo
     */
    public function alunos(Curriculo $curriculo)
    {   
        # Traz somente os alunos do Currículo
        $alunosCurriculo = Aluno::getAlunosCurriculo($curriculo);

        $disciplinasObrigatorias = DisciplinasObrigatoria::where('id_crl', $curriculo->id)->orderBy('coddis', 'asc')->get();
        $disciplinasOptativasEletivas = DisciplinasOptativasEletiva::where('id_crl', $curriculo->id)->orderBy('coddis', 'asc')->get();
        $disciplinasLicenciaturas = DisciplinasLicenciatura::where('id_crl', $curriculo->id)->orderBy('coddis', 'asc')->get();
        
        return view('curriculos.show', compact(
            'curriculo', 
            'disciplinasObrigatorias', 
            'disciplinasOptativasEletivas',
            'disciplinasLicenciaturas',
            'alunosCurriculo'
        ));       
    }

    /**
     * Lista os alunos que pertencem ao Currículo
     *
     * @param  int  $curriculo
     * @return view
     */
    public function print($id_crl)
    {   
        $curriculo = Curriculo::find($id_crl);     
        $disciplinasObrigatorias = DisciplinasObrigatoria::where('id_crl', $id_crl)->orderBy('coddis', 'asc')->get();
        $disciplinasOptativasEletivas = DisciplinasOptativasEletiva::where('id_crl', $id_crl)->orderBy('coddis', 'asc')->get();
        $disciplinasLicenciaturas = DisciplinasLicenciatura::where('id_crl', $id_crl)->orderBy('coddis', 'asc')->get();

        return view('curriculos.print', compact(
            'curriculo', 
            'disciplinasObrigatorias',
            'disciplinasOptativasEletivas',
            'disciplinasLicenciaturas'
        ));     
    }

    /**
     * Show the form for copy the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function copy(Curriculo $curriculo)
    {
        $disciplinasObrigatorias = DisciplinasObrigatoria::where('id_crl', $curriculo->id)->orderBy('coddis', 'asc')->get();
        $disciplinasOptativasEletivas = DisciplinasOptativasEletiva::where('id_crl', $curriculo->id)->orderBy('coddis', 'asc')->get();
        $disciplinasLicenciaturas = DisciplinasLicenciatura::where('id_crl', $curriculo->id)->orderBy('coddis', 'asc')->get();        
        $cursosHabilitacoes = Graduacao::obterCursosHabilitacoes(config('ccg.codUnd'));

        // Ordena por curso em ordem crescente
        array_multisort(array_column($cursosHabilitacoes, "codcur"), SORT_ASC, $cursosHabilitacoes);        
        
        $cursos = array();
        foreach ($cursosHabilitacoes as $curso) {
            if (!in_array(array('codcur' => $curso['codcur'], 'nomcur' => $curso['nomcur']), $cursos)) {
                array_push($cursos, array('codcur' => $curso['codcur'], 'nomcur' => $curso['nomcur']));
            }
        }

        $habilitacoes = array();
        foreach ($cursosHabilitacoes as $habilitacao) {
            if (!in_array(array('codhab' => $habilitacao['codhab'], 'nomhab' => $habilitacao['nomhab']), $habilitacoes)) {
                array_push($habilitacoes, array('codhab' => $habilitacao['codhab'], 'nomhab' => $habilitacao['nomhab']));
            }    
        }

        return view('curriculos.copy', compact(
            'curriculo', 
            'cursos', 
            'habilitacoes', 
            'cursosHabilitacoes',
            'disciplinasObrigatorias',
            'disciplinasOptativasEletivas',
            'disciplinasLicenciaturas'
        ));
    }

    /**
     * Store Copy Curriculo a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeCopy(Request $request, Curriculo $curriculo)
    {
        # Se não existe currículo, salva
        if (Curriculo::where(array(
                'codcur'    => $request->codcur,
                'codhab'    => $request->codhab,
                'dtainicrl' => Carbon::parse($request->dtainicrl) 
            ))->get()->count() == 0) {
            # Salvar o novo currículo
            $curriculoNew = new Curriculo;
            $curriculoNew->codcur = $request->codcur;
            $curriculoNew->codhab = $request->codhab;
            $curriculoNew->numcredisoptelt = $request->numcredisoptelt;
            $curriculoNew->numcredisoptliv = $request->numcredisoptliv;
            $curriculoNew->dtainicrl = Carbon::parse($request->dtainicrl);
            $curriculoNew->txtobs = $request->txtobs;
            $curriculo->numtotcredisoptelt = $request->numtotcredisoptelt;
            $curriculo->numtotcredisoptliv = $request->numtotcredisoptliv;
            $curriculoNew->save();            
            $disciplinasObrigatorias = DisciplinasObrigatoria::where('id_crl', $curriculo->id)->get()->toArray();    
            # Salvar disciplinas obrigaórias, se tiver
            if (!empty($disciplinasObrigatorias)) {                
                foreach ($disciplinasObrigatorias as $disciplinaObrigatoria) {
                    $disciplinaObrigatoriaNew = new DisciplinasObrigatoria;
                    $disciplinaObrigatoriaNew->id_crl = $curriculoNew->id;
                    $disciplinaObrigatoriaNew->coddis = $disciplinaObrigatoria['coddis'];
                    $disciplinaObrigatoriaNew->save();   
                    $disciplinasObrigatoriasEquivalentes = DisciplinasObrigatoriasEquivalente::where('id_dis_obr', $disciplinaObrigatoria['id'])->get()->toArray();                 
                    # Salvar disciplinas obrigatórias equivalentes, se tiver 
                    if (!empty($disciplinasObrigatoriasEquivalentes)) {
                        foreach ($disciplinasObrigatoriasEquivalentes as $disciplinaObrigatoriaEquivalente) {
                            $disciplinaObrigatoriaEquivalenteNew = new DisciplinasObrigatoriasEquivalente;
                            $disciplinaObrigatoriaEquivalenteNew->id_dis_obr = $disciplinaObrigatoriaNew->id;
                            $disciplinaObrigatoriaEquivalenteNew->coddis = $disciplinaObrigatoriaEquivalente['coddis'];
                            $disciplinaObrigatoriaEquivalenteNew->tipeqv = $disciplinaObrigatoriaEquivalente['tipeqv'];
                            $disciplinaObrigatoriaEquivalenteNew->save();
                        }    
                    }
                }
            }    
            $disciplinasEletivas = DisciplinasOptativasEletiva::where('id_crl', $curriculo->id)->get()->toArray();    
            # Salvar disciplinas eletivas, se tiver
            if (!empty($disciplinasEletivas)) {                
                foreach ($disciplinasEletivas as $disciplinaEletiva) {
                    $disciplinaEletivaNew = new DisciplinasOptativasEletiva;
                    $disciplinaEletivaNew->id_crl = $curriculoNew->id;
                    $disciplinaEletivaNew->coddis = $disciplinaEletiva['coddis'];
                    $disciplinaEletivaNew->save();                    
                }
            }             
            $disciplinasLicenciaturas = DisciplinasLicenciatura::where('id_crl', $curriculo->id)->get()->toArray();    
            # Salvar disciplinas licenciaturas, se tiver
            if (!empty($disciplinasLicenciaturas)) {                
                foreach ($disciplinasLicenciaturas as $disciplinaLicenciatura) {
                    $disciplinaLicenciaturaNew = new DisciplinasLicenciatura;
                    $disciplinaLicenciaturaNew->id_crl = $curriculoNew->id;
                    $disciplinaLicenciaturaNew->coddis = $disciplinaLicenciatura['coddis'];
                    $disciplinaLicenciaturaNew->save();                    
                    $disciplinasLicenciaturasEquivalentes = DisciplinasLicenciaturasEquivalente::where('id_dis_lic', $disciplinaLicenciatura['id'])->get()->toArray();
                    # Salvar disciplinas licenciaturas equivalentes, se tiver 
                    if (!empty($disciplinasLicenciaturasEquivalentes)) {
                        foreach ($disciplinasLicenciaturasEquivalentes as $disciplinaLicenciaturaEquivalente) {
                            $disciplinaLicenciaturaEquivalenteNew = new DisciplinasLicenciaturasEquivalente;
                            $disciplinaLicenciaturaEquivalenteNew->id_dis_lic = $disciplinaLicenciaturaNew->id;
                            $disciplinaLicenciaturaEquivalenteNew->coddis = $disciplinaLicenciaturaEquivalente['coddis'];
                            $disciplinaLicenciaturaEquivalenteNew->tipeqv = $disciplinaLicenciaturaEquivalente['tipeqv'];
                            $disciplinaLicenciaturaEquivalenteNew->save();
                        }    
                    }                    
                }
            } 
            # mensagem
            $msg = "Curriculo copiado com sucesso!"; 
            $tip = "alert-success";   
        # Se existe, avisa
        } else {
            $msg = "Já existe um currículo cadastrado com Curso = {$request->codcur}, 
                Habilitação = {$request->codhab} e ano de ingresso = " . substr(Carbon::parse($request->dtainicrl), 0, 4) . "!";
            $tip = "alert-danger";
        }

        $request->session()->flash($tip, $msg);
        return redirect('/curriculos');
    }       

    /**
     * Recuperar grade curricular no replicado e inserir no currículo do CCG
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function gradeCurricularJupiter(Curriculo $curriculo)
    {
        // Seleciona grade curricular atual
        $grade_atual = Graduacao::disciplinasCurriculo($curriculo->codcur, $curriculo->codhab);
        $disciplinasObrigatorias = array();
        $disciplinasOptativasEletivas = array();

        // Percorre todas disciplinas da grade
        // e separa em arrays específicos
        foreach ($grade_atual as $disciplina) {
            switch ($disciplina['tipobg']) {
                // Tipo Obrigatória
                case 'O':
                    array_push($disciplinasObrigatorias, $disciplina['coddis']);
                    break;
                // Tipo Optativa Eletiva
                case 'C':
                    array_push($disciplinasOptativasEletivas, $disciplina['coddis']);
                    break;
            }
        }
        // Método do controller específico para salvar a partir do Júpiter
        DisciplinasObrigatoriaController::storeJupiter($disciplinasObrigatorias, $curriculo);
        // Método do controller específico para salvar a partir do Júpiter
        DisciplinasOptativasEletivaController::storeJupiter($disciplinasOptativasEletivas, $curriculo);

        // Disciplinas Equivalentes
        $disciplinasEquivalentes = Graduacao::disciplinasEquivalentesCurriculo($curriculo->codcur, $curriculo->codhab);
        $equivalencias_temp = array(); // array auxiliar
        $disciplinasObrigatariosEquivalentesComTipo = array(); //array tratada com os tipos 'OU'/'E'

        foreach ($disciplinasEquivalentes as $disc) {
            $equivalencias_temp[$disc['coddis'] . '_' . $disc['tipobg']][$disc['codeqv']]['coddis_eq'][] = $disc['coddis_eq'];
        }

        foreach ($equivalencias_temp as $coddis => $codeqv) {
            // https://stackoverflow.com/questions/2681786/how-to-get-the-last-char-of-a-string-in-php
            if ($coddis[-1] === 'O') {
                foreach ($codeqv as $discEquivalente) {
                    if (count($discEquivalente['coddis_eq']) > 1) {
                        foreach ($discEquivalente as $coddis_eq) {
                            $disciplinasObrigatariosEquivalentesComTipo[$coddis]['E'] = $coddis_eq;
                        }
                    } else {
                        $disciplinasObrigatariosEquivalentesComTipo[$coddis]['OU'][] = $discEquivalente['coddis_eq'];
                    }
                }
            }
        }

        // Método do controller específico para salvar as equivalências a partir do Júpiter
        DisciplinasObrigatoriasEquivalenteController::storeEquivalenteJupiter($disciplinasObrigatariosEquivalentesComTipo, $curriculo);
        return redirect()->back()->with('alert-success', 'Disciplina(s) Obrigatória(s) e Eletiva(s) cadastrada(s) com sucesso!');
    }
 
}
