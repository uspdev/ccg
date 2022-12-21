<?php

namespace App\Http\Controllers;

use App\Ccg\Aluno;
use App\Ccg\Core;
use App\Models\AlunosDispensas;
use App\Models\DisciplinasLicenciatura;
use App\Models\DisciplinasLicenciaturasEquivalente;
use App\Models\DisciplinasObrigatoria;
use App\Models\DisciplinasObrigatoriasEquivalente;
use App\Replicado\Graduacao;
use Illuminate\Http\Request;

class GraduacaoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function search()
    {
        /**
         * Médoto que retorna o formulário de busca de aluno
         */
        return view('graduacao.busca');
    }

    public function searchAlunos($parteNome)
    {
        /**
         * Médoto que retorna o response/ajax com alunos ativos de graduação filtrando por parte do nome
         * e preenche o campo Nº USP com o codpes
         * @param string $parteNome
         * @return response $alunos
         */
        $alunos = Graduacao::ativos(config('ccg.codUnd'), $parteNome);
        return response($alunos);
    }

    /**
     * Médoto que retorna os dados acadêmicos do aluno
     * @param object $request
     * @return object $dadosAcademicos
     */
    public function dadosAcademicos(Request $request)
    {
        // dd($request->all());
        return self::creditos($request, 'graduacao.busca', false, $request->codpes);
    }

    public function aluno(Request $request)
    {
        /**
         * Médoto que retorna os créditos do aluno dada uma rota creditos/nº usp
         * @param object $request
         * @return view 'graduacao.busca'
         */
        $codpes = $request->route()->aluno;
        return self::creditos($request, 'graduacao.busca', false, $codpes);
    }

    /**
     * Médoto que retorna os creditos e disciplinas que faltam do aluno de graduação
     *
     * @param object $request
     * @param string $view
     * @param int $codpes
     * @return view $view
     */
    public static function creditos(Request $request, $view = 'aluno.creditos', $verPdf = false, $codpes = null)
    {
        # Obtém o nº USP do aluno a ser analisado
        $aluno = Aluno::getAluno($request, $codpes);
        $codpes = $aluno;
        # Verifica se o aluno está ativo na unidade
        if (Graduacao::verificarAluno($aluno) == false) {
            // Se não for aluno ativo de graduação na unidade
            $msg = "O nº USP $aluno não pertence a um aluno ativo de Graduação nesta unidade.";
            $request->session()->flash('alert-danger', $msg);
            return redirect('/busca');
        }
        # Obtém os dados acadêmicos
        $dadosAcademicos = Aluno::getDadosAcademicos($codpes);
        # Obtém o currículo do aluno
        $curriculoAluno = Aluno::getCurriculo($aluno);
        # Verifica se o aluno pertence a um currículo cadastrado
        if (empty($curriculoAluno)) {
            $nompes = $dadosAcademicos->nompes;
            $msg = "O aluno $aluno - $nompes não pertence a um currículo cadastrado neste sistema.";
            $request->session()->flash('alert-danger', $msg);
            return redirect('/busca');
        }
        # Obtém as discplinas obrigatórias no currículo do aluno
        $disciplinasObrigatorias = Aluno::getDisciplinasObrigatorias($curriculoAluno->id_crl);
        # Obtém as discplinas optativas eletivas no currículo do aluno
        $disciplinasOptativasEletivas = Aluno::getDisciplinasOptativasEletivas($curriculoAluno->id_crl);
        # Obtém as discplinas licenciaturas no currículo do aluno
        $disciplinasLicenciaturas = Aluno::getDisciplinasLicenciaturas($curriculoAluno->id_crl);
        # Obtém as discplinas obrigatórias equivalentes no currículo do aluno
        $disciplinasObrigatoriasEquivalentes = Aluno::getDisciplinasObrigatoriasEquivalentes($curriculoAluno->id_crl);
        # Obtém as discplinas licenciaturas equivalentes no currículo do aluno
        $disciplinasLicenciaturasEquivalentes = Aluno::getDisciplinasLicenciaturasEquivalentes($curriculoAluno->id_crl);
        # Obtém as discplinas concluídas do aluno
        $disciplinasConcluidas = Aluno::getDisciplinasConcluidas($aluno);
        # Obtém as discplinas obrigatórias concluídas do aluno
        $disciplinasObrigatoriasConcluidas = Aluno::getDisciplinasObrigatoriasConcluidas($aluno, $curriculoAluno->id_crl);
        # Obtém as disciplinas Obrigatórias que faltam
        $disciplinasObrigatoriasFaltam = array_diff($disciplinasObrigatorias, $disciplinasObrigatoriasConcluidas);
        # Obtém as discplinas optativas eletivas concluídas do aluno
        $disciplinasOptativasEletivasConcluidas = Aluno::getDisciplinasOptativasEletivasConcluidas($aluno, $curriculoAluno->id_crl);
        # Obtém as discplinas optativas eletivas que faltam/disponíveis do aluno
        $disciplinasOptativasEletivasFaltam = array_diff($disciplinasOptativasEletivas, $disciplinasOptativasEletivasConcluidas);
        # Obtém o total de créditos nas disciplinas optativas eletivas concluídas
        $numcredisoptelt = Aluno::getTotalCreditosDisciplinasOptativasEletivasConcluidas($aluno, $curriculoAluno->id_crl);
        # Obtém o total de créditos (aulas + trabalhos) nas disciplinas optativas eletivas concluídas
        $numtotcredisoptelt = Aluno::getTotalCreditosAulasTrabalhosDisciplinasOptativasEletivasConcluidas($aluno, $curriculoAluno->id_crl);
        # Obtém as discplinas licenciaturas concluídas do aluno
        $disciplinasLicenciaturasConcluidas = Aluno::getDisciplinasLicenciaturasConcluidas($aluno, $curriculoAluno->id_crl);
        # Obtém as disciplinas licenciaturas faltam
        $disciplinasLicenciaturasFaltam = array_diff($disciplinasLicenciaturas, $disciplinasLicenciaturasConcluidas);
        # Obtém as disciplinas exigidas no currículo
        $disciplinasCurriculo = array_merge($disciplinasObrigatorias, $disciplinasOptativasEletivasConcluidas, $disciplinasLicenciaturas);
        # Adiciona as disciplinas concluídas por equivalência em disciplinas obrigatórias ou licenciaturas concluídas
        $disciplinasConcluidasPorEquivalencia = array_diff($disciplinasConcluidas, $disciplinasCurriculo);
        # Obtém as disciplinas optativas livres concluídas
        if (($curriculoAluno->numcredisoptliv + $curriculoAluno->numtotcredisoptliv) == 0) {
            $disciplinasOptativasLivresConcluidas = array();
        } else {
            $disciplinasOptativasLivresConcluidas = array_diff($disciplinasConcluidas, $disciplinasCurriculo);
        }
        # Obtém o total de créditos nas disciplinas optativas livres concluídas
        $numcredisoptliv = Aluno::getTotalCreditosDisciplinasOptativasLivresConcluidas($aluno, $curriculoAluno->id_crl, $disciplinasOptativasLivresConcluidas);
        # Obtém o total de créditos (aulas + trabalhos) nas disciplinas optativas livres concluídas
        $numtotcredisoptliv = Aluno::getTotalCreditosAulasTrabalhosDisciplinasOptativasLivresConcluidas($aluno, $curriculoAluno->id_crl, $disciplinasOptativasLivresConcluidas);
        # Obtém as disciplinas concluídas diretamente do replicado
        $disciplinasConcluidas = Graduacao::disciplinasConcluidas($aluno, config('ccg.codUnd'));

        # Disciplinas obrigatórias equivalentes que faltam
        $disciplinasObrigatoriasEquivalentesFaltam = array();
        foreach ($disciplinasObrigatoriasFaltam as $disciplinaObrigatoriaFalta) {
            # verificar se $disciplinaObrigatoriaFalta tem equivalente
            $id_dis_obr = DisciplinasObrigatoria::where([
                'id_crl' => $curriculoAluno->id_crl,
                'coddis' => $disciplinaObrigatoriaFalta,
            ])->get()->toArray();
            $equivalentes = DisciplinasObrigatoriasEquivalente::where('id_dis_obr', $id_dis_obr[0]['id'])
                ->orderBy('coddis', 'asc')
                ->get();
            if ($equivalentes->isEmpty() == false) {
                # Monta array com as equivalentes
                $y = 0;
                foreach ($equivalentes as $equivalente) {
                    $disciplinasObrigatoriasEquivalentesFaltam[$disciplinaObrigatoriaFalta][$y] = [
                        $equivalente['coddis'],
                        $equivalente['tipeqv'],
                    ];
                    $y++;
                }
            }
        }

        # Disciplinas licenciaturas equivalentes que faltam
        $disciplinasLicenciaturasEquivalentesFaltam = array();
        foreach ($disciplinasLicenciaturasFaltam as $disciplinaLicenciaturaFalta) {
            # verificar se $disciplinaLicenciaturaFalta tem equivalente
            $id_dis_lic = DisciplinasLicenciatura::where([
                'id_crl' => $curriculoAluno->id_crl,
                'coddis' => $disciplinaLicenciaturaFalta,
            ])->get()->toArray();
            $equivalentes = DisciplinasLicenciaturasEquivalente::where('id_dis_lic', $id_dis_lic[0]['id'])
                ->orderBy('coddis', 'asc')
                ->get();
            if ($equivalentes->isEmpty() == false) {
                # Monta array com as equivalentes
                $y = 0;
                foreach ($equivalentes as $equivalente) {
                    $disciplinasLicenciaturasEquivalentesFaltam[$disciplinaLicenciaturaFalta][$y] = [
                        $equivalente['coddis'],
                        $equivalente['tipeqv'],
                    ];
                    $y++;
                }
            }
        }

        # Dispensas
        $dispensas = AlunosDispensas::where(['id_crl' => $curriculoAluno->id_crl, 'codpes' => $aluno])->get()->toArray();
        if (!empty($dispensas)) {
            $dispensas = explode(',', $dispensas[0]['coddis']);
        }

        # Obtém o gate para chavear o perfil entre secretaria e aluno
        $gate = Core::getGate();

        if ($verPdf == true) {
            $pdf = \PDF::loadView($view, compact(
                'gate', 'dadosAcademicos', 'curriculoAluno', 'disciplinasConcluidas', 'disciplinasObrigatoriasConcluidas',
                'disciplinasObrigatoriasFaltam', 'disciplinasOptativasEletivasConcluidas', 'disciplinasOptativasLivresConcluidas',
                'numcredisoptelt', 'disciplinasLicenciaturasConcluidas', 'disciplinasLicenciaturasFaltam', 'numcredisoptliv',
                'disciplinasOptativasEletivasFaltam', 'disciplinasObrigatoriasEquivalentesFaltam', 'disciplinasLicenciaturasEquivalentesFaltam',
                'dispensas', 'numtotcredisoptelt', 'numtotcredisoptliv'
            )
            );
            return $pdf->download(config('app.name') . $codpes . '.pdf');
        } else {
            return view($view, compact(
                'gate', 'dadosAcademicos', 'curriculoAluno', 'disciplinasConcluidas', 'disciplinasObrigatoriasConcluidas',
                'disciplinasObrigatoriasFaltam', 'disciplinasOptativasEletivasConcluidas', 'disciplinasOptativasLivresConcluidas',
                'numcredisoptelt', 'disciplinasLicenciaturasConcluidas', 'disciplinasLicenciaturasFaltam', 'numcredisoptliv',
                'disciplinasOptativasEletivasFaltam', 'dispensas', 'numtotcredisoptelt', 'numtotcredisoptliv'
            )
            );
        }
    }

    public function pdf(Request $request)
    {
        /**
         * @param object $request
         * @return view $view
         */
        $codpes = $request->route()->aluno;
        return self::creditos($request, 'aluno.pdf', true, $codpes);
    }
}
