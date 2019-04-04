<?php

namespace App\Ccg;

class Core
{
    /**
     * Classe para regra de acesso ao sistema
     * chaveando perfil secretaria e alunos
     */
    
    public static function getGate()
    {
        /**
         * Médoto que retorna o gate
         * @return string $gate
         */
        # Se APP_ENV = dev e CODPES_ALUNO não é vazio, desenvolvimento
        $gate = (config('ccg.envDev') === 'dev' and !empty(config('ccg.codpesAluno'))) ? 'secretaria' : 'alunos';
        return $gate;
    } 
}