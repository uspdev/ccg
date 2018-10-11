<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Arquivo padrão de configuração do CCG
    |--------------------------------------------------------------------------
    |
    | Configurações iniciais documendadas abaixo
    */

    /*
    |--------------------------------------------------------------------------
    | Prefixo dos códigos de disciplinas de graduação oferecidas da unidade
    |--------------------------------------------------------------------------
    |  
    |   Geralente são as siglas dos departamentos de ensino.
    |   Exemplo na ECA:
    |   Departamento de Comunicações e Artes = CAC
    |   Disciplinas da ECA ministradas em outras unidades = 270
    |   Devem ser separados por vírgula sem espaço
    */
    'arrCoddis' => explode(',', env('CODDIS')),

    /*
    |--------------------------------------------------------------------------
    | Nºs USP das pessoas que vão acessar como secretaria = Serviço de Graduação
    |--------------------------------------------------------------------------
    |   
    |   Devem ser colocados separados por vírgula sem espaço
    */
    'codpesAdmins' => env('CODPES_ADMINS'),

    /* TODO: DOCUMENTAR ESSE AQUI NO REPLICADO */
    'codUnd' => env('REPLICADO_CODUND'),

    /*
    |--------------------------------------------------------------------------
    |  Nº USP de um aluno ativo de Graduação da unidade
    |--------------------------------------------------------------------------
    |   Deve ser usado apenas em desenvolvimento
    */
    'codpesAluno' => env('CODPES_ALUNO', false),

    /*
    |--------------------------------------------------------------------------
    |  Deve usar o pacote wsFoto?
    |--------------------------------------------------------------------------
    |   Valores: true | false
    |   Default: false
    */
    'wsFoto' => env('WSFOTO', false),

    /* Pra que serve essa diretiva? */
    'envDev' => env('APP_ENV'),
];
