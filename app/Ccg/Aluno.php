<?php

namespace App\Ccg;

use App\Ccg\Core;
use App\Models\AlunosDispensas;
use App\Models\Curriculo;
use App\Models\DisciplinasLicenciatura;
use App\Models\DisciplinasLicenciaturasEquivalente;
use App\Models\DisciplinasObrigatoria;
use App\Models\DisciplinasObrigatoriasEquivalente;
use App\Models\DisciplinasOptativasEletiva;
use App\Replicado\Graduacao;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Classe para obter informações sobre alunos de graduação
 */
class Aluno
{
    /**
     * Médoto que retorna o nº USP do aluno a ser anlisado
     *
     * @param object $codpes
     * @return object $aluno
     */
    public static function getAluno(Request $request, $codpes)
    {
        $gate = Core::getGate();
        if ($codpes === null and $gate == 'alunos') {
            // Se o aluno logou no sistema
            $aluno = Auth::user()->id;
        } elseif ($gate == 'secretaria' and $codpes === null) {
            // Se a secretaria logou no sistema e clicou em Créditos do Aluno
            // Esta situação serve somente para simular o link Meus Créditos
            // que aparece somente para o aluno de graduação logado
            $aluno = config('ccg.codpesAluno');
        } else {
            // Quando recebe um nº USP
            $aluno = $codpes;
        }
        return $aluno;
    }

    /**
     * Médoto que retorna os dados acadêmicos dos alunos de graduação
     *
     * @param object $request
     * @param int $codpes
     * @return object $dadosAcademicos
     */
    public static function getDadosAcademicos($codpes)
    {
        $dadosAcademicos = (object) Graduacao::obterCursoAtivo($codpes);
        $dadosAcademicos->codpgm = Graduacao::programa($codpes)['codpgm'];
        return $dadosAcademicos;
    }

    /**
     * Método que retorna os dados curriculares dos alunos de graduação
     *
     * @param object $request
     * @param int $codpes
     * @return object $dadosAcademicos
     */
    public static function getCurriculo($codpes)
    {
        # Currículo do aluno
        $dadosAcademicos = self::getDadosAcademicos($codpes);
        $curriculo = Curriculo::where('codcur', $dadosAcademicos->codcur)
            ->where('codhab', $dadosAcademicos->codhab)
            ->whereYear('dtainicrl', substr($dadosAcademicos->dtainivin, 0, 4))
            ->get();
        # Verifica se o aluno pertence a um currículo cadastrado
        if ($curriculo->isEmpty()) {
            $curriculoAluno = array();
        } else {
            # Dados do Currículo do Aluno
            $curriculoAluno = (object) array(
                'id_crl' => $curriculo[0]->id,
                'numcredisoptelt' => $curriculo[0]->numcredisoptelt,
                'numcredisoptliv' => $curriculo[0]->numcredisoptliv,
                'dtainicrl' => substr($curriculo[0]->dtainicrl, 0, 4),
                'txtobs' => $curriculo[0]->txtobs,
                'numtotcredisoptelt' => $curriculo[0]->numtotcredisoptelt,
                'numtotcredisoptliv' => $curriculo[0]->numtotcredisoptliv,
            );
        }
        // dd($curriculo, $curriculoAluno);
        return $curriculoAluno;
    }

    /**
     * Médoto que retorna as disciplinas obrigatórias no currículo do aluno de graduação
     *
     * @param int $id_crl
     * @return array $disciplinasObrigatoriasCoddis
     */
    public static function getDisciplinasObrigatorias($id_crl)
    {
        $disciplinasObrigatorias = DisciplinasObrigatoria::where('id_crl', $id_crl)
            ->orderBy('coddis', 'asc')
            ->get()
            ->toArray();
        $disciplinasObrigatoriasCoddis = array();
        foreach ($disciplinasObrigatorias as $disciplinaObrigatoria) {
            array_push($disciplinasObrigatoriasCoddis, $disciplinaObrigatoria['coddis']);
        }
        sort($disciplinasObrigatoriasCoddis);
        return $disciplinasObrigatoriasCoddis;
    }

    /**
     * Médoto que retorna as disciplinas optativas eletivas no currículo do aluno de graduação
     *
     * @param int $id_crl
     * @return array $disciplinasOptativasEletivasCoddis
     */
    public static function getDisciplinasOptativasEletivas($id_crl)
    {
        $disciplinasOptativasEletivas = DisciplinasOptativasEletiva::where('id_crl', $id_crl)
            ->orderBy('coddis', 'asc')
            ->get()
            ->toArray();
        $disciplinasOptativasEletivasCoddis = array();
        foreach ($disciplinasOptativasEletivas as $disciplinaOptativaEletiva) {
            array_push($disciplinasOptativasEletivasCoddis, $disciplinaOptativaEletiva['coddis']);
        }
        sort($disciplinasOptativasEletivasCoddis);
        return $disciplinasOptativasEletivasCoddis;
    }

    /**
     * Médoto que retorna as disciplinas de licenciaturas no currículo do aluno de graduação
     *
     * @param int $id_crl
     * @return array $disciplinasLicenciaturasCoddis
     */
    public static function getDisciplinasLicenciaturas($id_crl)
    {
        $disciplinasLicenciaturas = DisciplinasLicenciatura::where('id_crl', $id_crl)
            ->orderBy('coddis', 'asc')
            ->get()
            ->toArray();
        $disciplinasLicenciaturasCoddis = array();
        foreach ($disciplinasLicenciaturas as $disciplinaLicenciatura) {
            array_push($disciplinasLicenciaturasCoddis, $disciplinaLicenciatura['coddis']);
        }
        sort($disciplinasLicenciaturasCoddis);
        return $disciplinasLicenciaturasCoddis;
    }

    /**
     * Médoto que retorna as disciplinas obrigatórias equivalentes no currículo do aluno de graduação
     *
     * @param int $id_crl
     * @return array $disciplinasObrigatoriasEquivalentes
     */
    public static function getDisciplinasObrigatoriasEquivalentes($id_crl)
    {
        $disciplinasObrigatoriasEquivalentes = array();
        $disciplinasObrigatorias = DisciplinasObrigatoria::where('id_crl', $id_crl)
            ->orderBy('coddis', 'asc')
            ->get()
            ->toArray();
        foreach ($disciplinasObrigatorias as $disciplinaObrigatoria) {
            # Consulta se tem equivalente
            $disciplinaObrigatoriaEquivalente = DisciplinasObrigatoriasEquivalente::where('id_dis_obr', $disciplinaObrigatoria['id'])
                ->orderBy('coddis', 'asc')
                ->get();
            # Se tem equivalentes
            if ($disciplinaObrigatoriaEquivalente->isEmpty() == false) {
                # Monta array com as equivalentes
                $y = 0;
                foreach ($disciplinaObrigatoriaEquivalente as $obrigatoriaEquivalente) {
                    $disciplinasObrigatoriasEquivalentes[$disciplinaObrigatoria['coddis']][$y] = $obrigatoriaEquivalente['coddis'];
                    $y++;
                }
            }
        }
        return $disciplinasObrigatoriasEquivalentes;
    }

    /**
     * Médoto que retorna as disciplinas de licenciaturas equivalentes no currículo do aluno de graduação
     *
     * @param int $id_crl
     * @return array $disciplinasLicenciaturasEquivalentes
     */public static function getDisciplinasLicenciaturasEquivalentes($id_crl)
    {
        $disciplinasLicenciaturasEquivalentes = array();
        $disciplinasLicenciaturas = DisciplinasLicenciatura::where('id_crl', $id_crl)
            ->orderBy('coddis', 'asc')
            ->get()
            ->toArray();
        foreach ($disciplinasLicenciaturas as $disciplinaLicenciatura) {
            # Consulta se tem equivalente
            $disciplinaLicenciaturaEquivalente = DisciplinasLicenciaturasEquivalente::where('id_dis_lic', $disciplinaLicenciatura['id'])
                ->orderBy('coddis', 'asc')
                ->get();
            # Se tem equivalentes
            if ($disciplinaLicenciaturaEquivalente->isEmpty() == false) {
                # Monta array com as equivalentes
                $y = 0;
                foreach ($disciplinaLicenciaturaEquivalente as $licenciaturaEquivalente) {
                    $disciplinasLicenciaturasEquivalentes[$disciplinaLicenciatura['coddis']][$y] = $licenciaturaEquivalente['coddis'];
                    $y++;
                }
            }
        }
        return $disciplinasLicenciaturasEquivalentes;
    }

    /**
     * Médoto que retorna as disciplinas concluídas do aluno de graduação
     *
     * @param int $aluno
     * @return array $disciplinasConcluidasCoddis
     */
    public static function getDisciplinasConcluidas($aluno)
    {
        $disciplinasConcluidas = Graduacao::disciplinasConcluidas($aluno, config('ccg.codUnd'));
        $disciplinasConcluidasCoddis = array();
        foreach ($disciplinasConcluidas as $disciplinaConcluida) {
            array_push($disciplinasConcluidasCoddis, $disciplinaConcluida['coddis']);
        }
        sort($disciplinasConcluidasCoddis);
        return $disciplinasConcluidasCoddis;
    }

    /**
     * Médoto que retorna as disciplinas obrigatorias concluídas do aluno de graduação
     *
     * @param int $aluno
     * @param int $id_crl
     * @return array $disciplinasObrigatoriasConcluidas
     */
    public static function getDisciplinasObrigatoriasConcluidas($aluno, $id_crl)
    {
        $disciplinasObrigatoriasRs = DisciplinasObrigatoria::where('id_crl', $id_crl)
            ->orderBy('coddis', 'asc')
            ->get()
            ->toArray();
        $disciplinasConcluidasRs = Graduacao::disciplinasConcluidas($aluno, config('ccg.codUnd'));
        $disObr = array();
        $disCon = array();
        $disObrCon = array();
        foreach ($disciplinasObrigatoriasRs as $disciplinaObrigatoriaRs) {
            array_push($disObr, $disciplinaObrigatoriaRs['coddis']);
        }
        $lin = 1;
        foreach ($disciplinasConcluidasRs as $disciplinaConcluidaRs) {
            array_push($disCon, $disciplinaConcluidaRs['coddis']);
            foreach ($disObr as $dObr) {
                if ($disciplinaConcluidaRs['coddis'] == $dObr) {
                    array_push($disObrCon, $dObr);
                    // echo "$lin $dObr concluida<br />";
                    $lin++;
                }
            }
        }
        $disObrFal = array_diff($disObr, $disObrCon);
        foreach ($disObrFal as $dObrFal) {
            $disObrFalId = DisciplinasObrigatoria::select('id')
                ->where(['id_crl' => $id_crl, 'coddis' => $dObrFal])
                ->get()
                ->toArray();
            $dObrFalId = $disObrFalId[0]['id'];
            $disciplinasObrigatoriasEquivalentes = DisciplinasObrigatoriasEquivalente::where('id_dis_obr', $dObrFalId)
                ->with('disciplinasObrigatorias')
                ->get()
                ->toArray();
            if (count($disciplinasObrigatoriasEquivalentes) > 0) {
                $eqvTip = '';
                $eqvDis = '';
                $eqvSts = 'não';
                foreach ($disciplinasObrigatoriasEquivalentes as $disciplinaEquivalente) {
                    if ($disciplinaEquivalente['tipeqv'] == 'OU') {
                        $eqvTip = 'OU';
                    } else {
                        $eqvTip = 'E';
                    }
                    $eqvDis .= $disciplinaEquivalente['coddis'];
                    # equivalencia OU
                    if ((in_array($disciplinaEquivalente['coddis'], $disCon)) and ($eqvTip == 'OU')) {
                        $eqvDis .= " ";
                        $eqvSts = '';
                        array_push($disObrCon, $dObrFal);
                        unset($disObrFal[array_search($dObrFal, $disObrFal)]);
                        # equivalencia E
                    } elseif ($eqvTip == 'E') {
                        $eqvDis .= " ";
                    }
                }
                # equivalencia E
                $arrEqvE = array();
                $disObrOK = '';
                if ($eqvTip == 'E') {
                    $arrEqvE = explode(' ', trim($eqvDis));
                    foreach ($arrEqvE as $eqvE) {
                        if (in_array($eqvE, $disCon)) {
                            $disObrOK .= 'OK ';
                            //echo "$eqvE<br />";
                        } else {
                            $disObrOK .= '* ';
                        }
                    }
                    # Cumpriu toda equivalencia E?
                    if (!in_array('*', explode(' ', trim($disObrOK)))) {
                        array_push($disObrCon, $dObrFal);
                        unset($disObrFal[array_search($dObrFal, $disObrFal)]);
                    }
                }
                //dd($disObrOK);
                if ($eqvSts == '') {
                    // echo "$lin $dObrFal $eqvSts concluida através de equivalência do tipo $eqvTip com $eqvDis<br />";
                } else {
                    // echo "$lin $dObrFal <strong>$eqvSts concluida</strong> e tem equivalência do tipo $eqvTip com $eqvDis<br />";
                }
            } else {
                // echo "$lin $dObrFal <strong>não concluida</strong> e não tem equivalência<br />";
            }
            $lin++;
        }
        sort($disObrCon);
        return $disObrCon;
    }

    /**
     * Médoto que retorna as disciplinas optativas eletivas concluídas do aluno de graduação
     *
     * @param int $aluno
     * @param int $id_crl
     * @return array $disciplinasOptativasEletivasConcluidas
     */
    public static function getDisciplinasOptativasEletivasConcluidas($aluno, $id_crl)
    {
        $disciplinasOptativasEletivasRs = DisciplinasOptativasEletiva::where('id_crl', $id_crl)
            ->orderBy('coddis', 'asc')
            ->get()
            ->toArray();
        $disciplinasOptativasEletivasConcluidas = array();
        $disciplinasConcluidasRs = Graduacao::disciplinasConcluidas($aluno, config('ccg.codUnd'));
        foreach ($disciplinasConcluidasRs as $disciplinaConcluida) {
            foreach ($disciplinasOptativasEletivasRs as $disciplinaOptativaEletiva) {
                if ($disciplinaConcluida['coddis'] == $disciplinaOptativaEletiva['coddis']) {
                    array_push($disciplinasOptativasEletivasConcluidas, $disciplinaConcluida['coddis']);
                }
            }
        }
        return $disciplinasOptativasEletivasConcluidas;
    }

    /**
     * Médoto que retorna o total de créditos nas disciplinas optativas eletivas concluídas do aluno de graduação
     *
     * @param int $aluno
     * @param int $id_crl
     * @return int $numcredisoptelt
     */
    public static function getTotalCreditosDisciplinasOptativasEletivasConcluidas($aluno, $id_crl)
    {
        $disciplinasOptativasEletivasRs = DisciplinasOptativasEletiva::where('id_crl', $id_crl)
            ->orderBy('coddis', 'asc')
            ->get()
            ->toArray();
        $numcredisoptelt = 0;
        $disciplinasConcluidasRs = Graduacao::disciplinasConcluidas($aluno, config('ccg.codUnd'));
        $dispensas = AlunosDispensas::where(['id_crl' => $id_crl, 'codpes' => $aluno])->get()->toArray();
        if (!empty($dispensas)) {
            $dispensas = explode(',', $dispensas[0]['coddis']);
        }
        foreach ($disciplinasConcluidasRs as $disciplinaConcluida) {
            foreach ($disciplinasOptativasEletivasRs as $disciplinaOptativaEletiva) {
                if ($disciplinaConcluida['coddis'] == $disciplinaOptativaEletiva['coddis']) {
                    if (!in_array($disciplinaConcluida['coddis'], $dispensas)) {
                        # Total de Créditos Concluídos Optativas Eletivas
                        $numcredisoptelt += $disciplinaConcluida['creaul'];
                    }
                }
            }
        }
        return $numcredisoptelt;
    }

    /**
     * Médoto que retorna o total de créditos (aulas + trabalhos) nas disciplinas optativas eletivas concluídas do aluno de graduação
     *
     * @param int $aluno
     * @param int $id_crl
     * @return int $numtotcredisoptelt
     */
    public static function getTotalCreditosAulasTrabalhosDisciplinasOptativasEletivasConcluidas($aluno, $id_crl)
    {
        $disciplinasOptativasEletivasRs = DisciplinasOptativasEletiva::where('id_crl', $id_crl)
            ->orderBy('coddis', 'asc')
            ->get()
            ->toArray();
        $numtotcredisoptelt = 0;
        $disciplinasConcluidasRs = Graduacao::disciplinasConcluidas($aluno, config('ccg.codUnd'));
        $dispensas = AlunosDispensas::where(['id_crl' => $id_crl, 'codpes' => $aluno])->get()->toArray();
        if (!empty($dispensas)) {
            $dispensas = explode(',', $dispensas[0]['coddis']);
        }
        foreach ($disciplinasConcluidasRs as $disciplinaConcluida) {
            foreach ($disciplinasOptativasEletivasRs as $disciplinaOptativaEletiva) {
                if ($disciplinaConcluida['coddis'] == $disciplinaOptativaEletiva['coddis']) {
                    if (!in_array($disciplinaConcluida['coddis'], $dispensas)) {
                        # Total de Créditos Concluídos Optativas Eletivas
                        $numtotcredisoptelt += $disciplinaConcluida['creaul'] + $disciplinaConcluida['cretrb'];
                    }
                }
            }
        }
        return $numtotcredisoptelt;
    }

    /**
     * Médoto que retorna as disciplinas licenciaturas concluídas do aluno de graduação
     *
     * @param int $aluno
     * @param int $id_crl
     * @return array $disciplinasLicenciaturasConcluidas
     */
    public static function getDisciplinasLicenciaturasConcluidas($aluno, $id_crl)
    {
        $disciplinasLicenciaturasRs = DisciplinasLicenciatura::where('id_crl', $id_crl)
            ->orderBy('coddis', 'asc')
            ->get()
            ->toArray();
        $disciplinasConcluidasRs = Graduacao::disciplinasConcluidas($aluno, config('ccg.codUnd'));
        $disLic = array();
        $disCon = array();
        $disLicCon = array();
        foreach ($disciplinasLicenciaturasRs as $disciplinaLicenciaturaRs) {
            array_push($disLic, $disciplinaLicenciaturaRs['coddis']);
        }
        $lin = 1;
        foreach ($disciplinasConcluidasRs as $disciplinaConcluidaRs) {
            array_push($disCon, $disciplinaConcluidaRs['coddis']);
            foreach ($disLic as $dLic) {
                if ($disciplinaConcluidaRs['coddis'] == $dLic) {
                    array_push($disLicCon, $dLic);
                    // echo "$lin $dLic concluida<br />";
                    $lin++;
                }
            }
        }
        $disLicFal = array_diff($disLic, $disLicCon);
        foreach ($disLicFal as $dLicFal) {
            $disLicFalId = DisciplinasLicenciatura::select('id')
                ->where(['id_crl' => $id_crl, 'coddis' => $dLicFal])
                ->get()
                ->toArray();
            $dLicFalId = $disLicFalId[0]['id'];
            $disciplinasLicenciaturasEquivalentes = DisciplinasLicenciaturasEquivalente::where('id_dis_lic', $dLicFalId)
                ->with('disciplinasLicenciaturas')
                ->get()
                ->toArray();
            if (count($disciplinasLicenciaturasEquivalentes) > 0) {
                $eqvTip = '';
                $eqvDis = '';
                $eqvSts = 'não';
                foreach ($disciplinasLicenciaturasEquivalentes as $disciplinaEquivalente) {
                    if ($disciplinaEquivalente['tipeqv'] == 'OU') {
                        $eqvTip = 'OU';
                    } else {
                        $eqvTip = 'E';
                    }
                    $eqvDis .= $disciplinaEquivalente['coddis'];
                    # equivalencia OU
                    if ((in_array($disciplinaEquivalente['coddis'], $disCon)) and ($eqvTip == 'OU')) {
                        $eqvDis .= " ";
                        $eqvSts = '';
                        array_push($disLicCon, $dLicFal);
                        unset($disLicFal[array_search($dLicFal, $disLicFal)]);
                        # equivalencia E
                    } elseif ($eqvTip == 'E') {
                        $eqvDis .= " ";
                    }
                }
                # equivalencia E
                $arrEqvE = array();
                $disLicOK = '';
                if ($eqvTip == 'E') {
                    $arrEqvE = explode(' ', trim($eqvDis));
                    foreach ($arrEqvE as $eqvE) {
                        if (in_array($eqvE, $disCon)) {
                            $disLicOK .= 'OK ';
                            //echo "$eqvE<br />";
                        } else {
                            $disLicOK .= '* ';
                        }
                    }
                    # Cumpriu toda equivalencia E?
                    if (!in_array('*', explode(' ', trim($disLicOK)))) {
                        array_push($disLicCon, $dLicFal);
                        unset($disLicFal[array_search($dLicFal, $disLicFal)]);
                    }
                }
                //dd($disLicOK);
                if ($eqvSts == '') {
                    // echo "$lin $dLicFal $eqvSts concluida através de equivalência do tipo $eqvTip com $eqvDis<br />";
                } else {
                    // echo "$lin $dLicFal <strong>$eqvSts concluida</strong> e tem equivalência do tipo $eqvTip com $eqvDis<br />";
                }
            } else {
                // echo "$lin $dLicFal <strong>não concluida</strong> e não tem equivalência<br />";
            }
            $lin++;
        }
        sort($disLicCon);
        return $disLicCon;
    }

    /**
     * Médoto que retorna o total de créditos nas disciplinas optativas livres concluídas do aluno de graduação
     *
     * @param int $aluno
     * @param int $id_crl
     * @param array $disciplinasOptativasLivresConcluidas
     * @return int $numcredisoptliv
     */
    public static function getTotalCreditosDisciplinasOptativasLivresConcluidas($aluno, $id_crl, $disciplinasOptativasLivresConcluidas)
    {
        $numcredisoptliv = 0;
        $disciplinasConcluidasRs = Graduacao::disciplinasConcluidas($aluno, config('ccg.codUnd'));
        $dispensas = AlunosDispensas::where(['id_crl' => $id_crl, 'codpes' => $aluno])->get()->toArray();
        if (!empty($dispensas)) {
            $dispensas = explode(',', $dispensas[0]['coddis']);
        }
        foreach ($disciplinasConcluidasRs as $disciplinaConcluida) {
            foreach ($disciplinasOptativasLivresConcluidas as $disciplinaOptativaLivre) {
                if ($disciplinaConcluida['coddis'] == $disciplinaOptativaLivre) {
                    # Verificar se é equivalente
                    if (self::getConcluiuEquivalente($disciplinaConcluida['coddis'], $id_crl, 'Obrigatoria') == 0 and
                        self::getConcluiuEquivalente($disciplinaConcluida['coddis'], $id_crl, 'Licenciatura') == 0) {
                        if (!in_array($disciplinaConcluida['coddis'], $dispensas)) {
                            # Total de Créditos Concluídos Optativas Livres
                            $numcredisoptliv += $disciplinaConcluida['creaul'];
                        }
                    }
                }
            }
        }
        // Créditos atribuídos em disciplinas livres cursadas no exterior
        $disciplinasConcluidasAE = Graduacao::creditosDisciplinasConcluidasAproveitamentoEstudosExterior($aluno, config('ccg.codUnd'));
        foreach ($disciplinasConcluidasAE as $disciplinaConcluida) {
            $numcredisoptliv += $disciplinaConcluida['creaulatb'];
        }
        return $numcredisoptliv;
    }

    /**
     * Médoto que retorna o total de créditos (aulas + trabalhos) nas disciplinas optativas livres concluídas do aluno de graduação
     *
     * @param int $aluno
     * @param int $id_crl
     * @param array $disciplinasOptativasLivresConcluidas
     * @return int $numtotcredisoptliv
     */
    public static function getTotalCreditosAulasTrabalhosDisciplinasOptativasLivresConcluidas($aluno, $id_crl, $disciplinasOptativasLivresConcluidas)
    {
        $numtotcredisoptliv = 0;
        $disciplinasConcluidasRs = Graduacao::disciplinasConcluidas($aluno, config('ccg.codUnd'));
        $dispensas = AlunosDispensas::where(['id_crl' => $id_crl, 'codpes' => $aluno])->get()->toArray();
        if (!empty($dispensas)) {
            $dispensas = explode(',', $dispensas[0]['coddis']);
        }
        foreach ($disciplinasConcluidasRs as $disciplinaConcluida) {
            foreach ($disciplinasOptativasLivresConcluidas as $disciplinaOptativaLivre) {
                if ($disciplinaConcluida['coddis'] == $disciplinaOptativaLivre) {
                    # Verificar se é equivalente
                    if (self::getConcluiuEquivalente($disciplinaConcluida['coddis'], $id_crl, 'Obrigatoria') == 0 and
                        self::getConcluiuEquivalente($disciplinaConcluida['coddis'], $id_crl, 'Licenciatura') == 0) {
                        if (!in_array($disciplinaConcluida['coddis'], $dispensas)) {
                            # Total de Créditos Concluídos Optativas Livres
                            $numtotcredisoptliv += $disciplinaConcluida['creaul'] + $disciplinaConcluida['cretrb'];
                        }
                    }
                }
            }
        }
        // Créditos atribuídos em disciplinas livres cursadas no exterior
        $disciplinasConcluidasAE = Graduacao::creditosDisciplinasConcluidasAproveitamentoEstudosExterior($aluno, config('ccg.codUnd'));
        foreach ($disciplinasConcluidasAE as $disciplinaConcluida) {
            $numtotcredisoptliv += $disciplinaConcluida['creaulatb'];
        }
        return $numtotcredisoptliv;
    }

    /**
     * Médoto que retorna os alunos de um currículo
     *
     * @param array $curriculo
     * @return array $alunosCurriculo
     */
    public static function getAlunosCurriculo($curriculo)
    {
        $alunosCurriculo = array();
        # Busca os alunos da unidade
        $alunosUnidade = Graduacao::listarAtivos();
        foreach ($alunosUnidade as $alunoUnidade) {
            $dadosAluno = Graduacao::curso($alunoUnidade['codpes'], config('ccg.codUnd'));
            if (($dadosAluno['codcurgrd'] == $curriculo['codcur']) and
                ($dadosAluno['codhab'] == $curriculo['codhab']) and
                (substr($dadosAluno['dtainivin'], 0, 4) == substr($curriculo['dtainicrl'], 0, 4))
            ) {
                array_push($alunosCurriculo, [
                    'codpes' => $dadosAluno['codpes'],
                    'nompes' => $dadosAluno['nompes'],
                ]);
            }
        }
        return $alunosCurriculo;
    }

    /**
     * Médoto que verifica se concluiu a disciplina equivalente
     *
     * Retorna 1 se foi concluida
     *
     * @param string $coddis
     * @param int $id_crl
     * @param string $table values 'Obrigatoria' or 'Licenciatura'
     * @return int $consulta
     */
    public static function getConcluiuEquivalente($coddis, $id_crl, $table)
    {
        $id_dis = ($table == 'Obrigatoria') ? 'obr' : 'lic';
        $consulta = DB::table("Disciplinas{$table}sEquivalentes")
            ->join("Disciplinas{$table}s", "Disciplinas{$table}sEquivalentes.id_dis_{$id_dis}", '=', "Disciplinas{$table}s.id")
            ->where(array(
                "Disciplinas{$table}sEquivalentes.coddis" => $coddis,
                "Disciplinas{$table}s.id_crl" => $id_crl,
            ))
            ->get()->count();

        return $consulta;
    }
}
