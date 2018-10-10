<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Arquivo padrão de configuração 
    |--------------------------------------------------------------------------
    |
    | Configurações iniciais documendadas abaixo
    */
    'arrCoddis' => explode(',', env('CODDIS')),

    'codpesAdmins' => env('CODPES_ADMINS'),

    'codUnd' => env('REPLICADO_CODUND'),

    'codpesAluno' => env('CODPES_ALUNO'),

    'wsFoto' => env('WSFOTO'),

    'envDev' => env('APP_ENV'),
];
