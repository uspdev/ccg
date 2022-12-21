<?php

namespace App\Ccg;

/**
 * Classe para regra de acesso ao sistema
 * chaveando perfil secretaria e alunos
 */
class Core
{

    /**
     * Médoto que retorna o gate
     * 
     * @return string $gate
     */public static function getGate()
    {
        # Se APP_ENV = dev e CODPES_ALUNO não é vazio, desenvolvimento
        $gate = (config('ccg.envDev') === 'dev' and !empty(config('ccg.codpesAluno'))) ? 'secretaria' : 'alunos';
        return $gate;
    }
}
