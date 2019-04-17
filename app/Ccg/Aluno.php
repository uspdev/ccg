<?php

namespace App\Ccg;

use Illuminate\Http\Request;
use Uspdev\Replicado\Connection;
use Uspdev\Replicado\Graduacao;
use Auth;
use App\Ccg\Core;
use App\Curriculo;
use App\DisciplinasObrigatoria;
use App\DisciplinasOptativasEletiva;
use App\DisciplinasLicenciatura;
use App\DisciplinasObrigatoriasEquivalente;
use App\DisciplinasLicenciaturasEquivalente;

class Aluno 
{
    /**
     * Classe para obter informações sobre alunos de graduação
     */
    
    public static function getAluno(Request $request, $codpes = null)
    {
        /**
         * Médoto que retorna o nº USP do aluno a ser anlisado
         * @param object $codpes
         * @return object $aluno
         */
        $gate = Core::getGate();
        if ($codpes === null and $gate == 'alunos') {
            // Se o aluno logou no sistema
            $aluno = Auth::user()->id;
        } elseif ($gate == 'secretaria') { 
            // Se a secretaria logou no sistema e clicou em Créditos do Aluno
            // Esta situação serve somente para simular o link Meus Créditos
            // que aparece somente para o aluno de graduação logado
            $aluno = config('ccg.codpesAluno');
        } else {
            // Quando recebe um nº USP
            $aluno = $codpes;
        }
        if (Graduacao::verifica($aluno, config('ccg.codUnd')) == false) {
            // Se não for aluno ativo de graduação na unidade
            $msg = "O nº USP $aluno não pertence a um aluno ativo de Graduação nesta unidade.";
            $request->session()->flash('alert-danger', $msg);
            return view('index');
        }
        return $aluno;
    }    
    
    public static function getDadosAcademicos(Request $request, $codpes = null) 
    {
        /**
         * Médoto que retorna os dados acadêmicos dos alunos de graduação
         * @param object $request 
         * @param int $codpes
         * @return object $dadosAcademicos
         */
        // Verifica se o nº USP vem do formulário de busca ($request)
        // ou de uma chamada do sistema ($codpes)
        $codpes = ($codpes === null) ? $request->codpes : $codpes;
        // É aluno de graduação ATIVO da unidade? 
        if (Graduacao::verifica($codpes, config('ccg.codUnd'))) {
            $dadosAcademicos = (object) array(
                'codpes'    => Graduacao::curso($codpes, config('ccg.codUnd'))['codpes'],
                'nompes'    => Graduacao::curso($codpes, config('ccg.codUnd'))['nompes'],
                'codcur'    => Graduacao::curso($codpes, config('ccg.codUnd'))['codcur'],
                'nomcur'    => Graduacao::curso($codpes, config('ccg.codUnd'))['nomcur'],
                'codhab'    => Graduacao::curso($codpes, config('ccg.codUnd'))['codhab'],
                'nomhab'    => Graduacao::curso($codpes, config('ccg.codUnd'))['nomhab'],
                'dtainivin' => Graduacao::curso($codpes, config('ccg.codUnd'))['dtainivin'],
                'codpgm'    => Graduacao::programa($codpes)['codpgm'],
            );
        } else {
            $msg = "O nº USP $codpes não pertence a um aluno ativo de Graduação nesta unidade.";
            $request->session()->flash('alert-danger', $msg);
            return redirect('/busca');
        }
        return $dadosAcademicos;
    }

    public static function getCurriculo(Request $request, $codpes)
    {
        /**
         * Médoto que retorna os dados curriculares dos alunos de graduação
         * @param object $request 
         * @param int $codpes
         * @return object $dadosAcademicos
         */
        # Currículo do aluno
        $curriculo = Curriculo::where('codcur', self::getDadosAcademicos($request, $codpes)->codcur)
            ->where('codhab', self::getDadosAcademicos($request, $codpes)->codhab)
            ->whereYear('dtainicrl', substr(self::getDadosAcademicos($request, $codpes)->dtainivin, 0, 4))
            ->get(); 
        # Verifica se o aluno pertence a um currículo cadastrado
        if ($curriculo->isEmpty()) {
            $msg = "O aluno $codpes - {self::getDadosAcademicos($request, $codpes)->nompes} não pertence a um currículo cadastrado neste sistema.";
            $request->session()->flash('alert-danger', $msg);
            $curriculos = Curriculo::all();
            return view('curriculos.index', compact('curriculos'));
        }        
        # Dados do Currículo do Aluno
        $curriculoAluno = (object) array(
            'id_crl' => $curriculo[0]->id,
            'numcredisoptelt' => $curriculo[0]->numcredisoptelt,
            'numcredisoptliv' => $curriculo[0]->numcredisoptliv,
            'dtainicrl' => substr($curriculo[0]->dtainicrl, 0, 4)
        );
        return $curriculoAluno;
    }

    public static function getDisciplinasObrigatorias($id_crl)
    {
        /**
         * Médoto que retorna as disciplinas obrigatórias no currículo do aluno de graduação
         * @param int $id_crl
         * @return array $disciplinasObrigatoriasCoddis
         */
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

    public static function getDisciplinasOptativasEletivas($id_crl)
    {
        /**
         * Médoto que retorna as disciplinas optativas eletivas no currículo do aluno de graduação
         * @param int $id_crl
         * @return array $disciplinasOptativasEletivasCoddis
         */
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

    public static function getDisciplinasLicenciaturas($id_crl)
    {
        /**
         * Médoto que retorna as disciplinas de licenciaturas no currículo do aluno de graduação
         * @param int $id_crl
         * @return array $disciplinasLicenciaturasCoddis
         */ 
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

    public static function getDisciplinasObrigatoriasEquivalentes($id_crl)
    {
        /**
         * Médoto que retorna as disciplinas obrigatórias equivalentes no currículo do aluno de graduação
         * @param int $id_crl
         * @return array $disciplinasObrigatoriasEquivalentes
         */ 
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

    public static function getDisciplinasLicenciaturasEquivalentes($id_crl) 
    {
        /**
         * Médoto que retorna as disciplinas de licenciaturas equivalentes no currículo do aluno de graduação
         * @param int $id_crl
         * @return array $disciplinasLicenciaturasEquivalentes
         */ 
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

    public static function getDisciplinasConcluidas($aluno)
    {
        /**
         * Médoto que retorna as disciplinas concluídas do aluno de graduação
         * @param int $aluno
         * @return array $disciplinasConcluidasCoddis
         */ 
        $disciplinasConcluidas = Graduacao::disciplinasConcluidas($aluno, config('ccg.codUnd'));
        $disciplinasConcluidasCoddis = array();
        foreach ($disciplinasConcluidas as $disciplinaConcluida) {
            array_push($disciplinasConcluidasCoddis, $disciplinaConcluida['coddis']);
        }
        sort($disciplinasConcluidasCoddis);
        return $disciplinasConcluidasCoddis;
    }

    public static function getDisciplinasObrigatoriasConcluidas($aluno, $id_crl)
    {
        /**
         * Médoto que retorna as disciplinas obrigatorias concluídas do aluno de graduação
         * @param int $aluno
         * @param int $id_crl
         * @return array $disciplinasObrigatoriasConcluidas
         */ 
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
                    $lin ++;
                }
            }
        }
        $disObrFal = array_diff($disObr, $disObrCon);
        foreach ($disObrFal as $dObrFal) {
            $disObrFalId = DisciplinasObrigatoria::select('id')
                ->where(['id_crl' => 5, 'coddis' => $dObrFal])
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
                    if (in_array($disciplinaEquivalente['coddis'], $disCon)) {
                        $eqvDis .= "<strong><<</strong>";
                        $eqvSts = '';
                        array_push($disObrCon, $dObrFal);
                        unset($disObrFal[array_search($dObrFal, $disObrFal)]);
                    } 
                }
                if ($eqvSts == '') {
                    // echo "$lin $dObrFal $eqvSts concluida através de equivalência do tipo $eqvTip com $eqvDis<br />";
                } else {
                    // echo "$lin $dObrFal <strong>$eqvSts concluida</strong> e tem equivalência do tipo $eqvTip com $eqvDis<br />";
                } 
            } else {
                // echo "$lin $dObrFal <strong>não concluida</strong> e não tem equivalência<br />";
            }  
            $lin ++; 
        }
        return $disObrCon;
    }

    public static function getDisciplinasOptativasEletivasConcluidas($aluno, $id_crl)
    {
        /**
         * Médoto que retorna as disciplinas optativas eletivas concluídas do aluno de graduação
         * @param int $aluno
         * @param int $id_crl
         * @return array $disciplinasOptativasEletivasConcluidas
         */ 
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

    public static function getTotalCreditosDisciplinasOptativasEletivasConcluidas($aluno, $id_crl)
    {
        /**
         * Médoto que retorna o total de créditos nas disciplinas optativas eletivas concluídas do aluno de graduação
         * @param int $aluno
         * @param int $id_crl
         * @return int $numcredisoptelt
         */
        $disciplinasOptativasEletivasRs = DisciplinasOptativasEletiva::where('id_crl', $id_crl)
            ->orderBy('coddis', 'asc')
            ->get()
            ->toArray();
        $numcredisoptelt = 0;
        $disciplinasConcluidasRs = Graduacao::disciplinasConcluidas($aluno, config('ccg.codUnd'));
        foreach ($disciplinasConcluidasRs as $disciplinaConcluida) {
            foreach ($disciplinasOptativasEletivasRs as $disciplinaOptativaEletiva) {
                if ($disciplinaConcluida['coddis'] == $disciplinaOptativaEletiva['coddis']) {
                    # Total de Créditos Concluídos Optativas Eletivas
                    $numcredisoptelt += $disciplinaConcluida['creaul'];
                }
            }
        }
        return $numcredisoptelt;
    }

    public static function getDisciplinasLicenciaturasConcluidas($aluno, $id_crl)
    {
        /**
         * Médoto que retorna as disciplinas licenciaturas concluídas do aluno de graduação
         * @param int $aluno
         * @param int $id_crl
         * @return array $disciplinasLicenciaturasConcluidas
         */ 
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
                    $lin ++;
                }
            }
        }
        $disLicFal = array_diff($disLic, $disLicCon);
        foreach ($disLicFal as $dLicFal) {
            $disLicFalId = DisciplinasLicenciatura::select('id')
                ->where(['id_crl' => 5, 'coddis' => $dLicFal])
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
                    if (in_array($disciplinaEquivalente['coddis'], $disCon)) {
                        $eqvDis .= "<strong><<</strong>";
                        $eqvSts = '';
                        array_push($disLicCon, $dLicFal);
                        unset($disLicFal[array_search($dLicFal, $disLicFal)]);
                    } 
                }
                if ($eqvSts == '') {
                    // echo "$lin $dLicFal $eqvSts concluida através de equivalência do tipo $eqvTip com $eqvDis<br />";
                } else {
                    // echo "$lin $dLicFal <strong>$eqvSts concluida</strong> e tem equivalência do tipo $eqvTip com $eqvDis<br />";
                } 
            } else {
                // echo "$lin $dLicFal <strong>não concluida</strong> e não tem equivalência<br />";
            }  
            $lin ++; 
        }
        return $disLicCon;
    }

    public static function getTotalCreditosDisciplinasOptativasLivresConcluidas($aluno, $id_crl, $disciplinasOptativasLivresConcluidas)
    {
        /**
         * Médoto que retorna o total de créditos nas disciplinas optativas livres concluídas do aluno de graduação
         * @param int $aluno
         * @param int $id_crl
         * @param array $disciplinasOptativasLivresConcluidas
         * @return int $numcredisoptliv
         */
        $numcredisoptliv = 0;
        $disciplinasConcluidasRs = Graduacao::disciplinasConcluidas($aluno, config('ccg.codUnd'));
        foreach ($disciplinasConcluidasRs as $disciplinaConcluida) {
            foreach ($disciplinasOptativasLivresConcluidas as $disciplinaOptativaLivre) {
                if ($disciplinaConcluida['coddis'] == $disciplinaOptativaLivre) {
                    # Total de Créditos Concluídos Optativas Livres
                    $numcredisoptliv += $disciplinaConcluida['creaul'];
                }
            }
        }
        return $numcredisoptliv;
    }
}