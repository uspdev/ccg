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
    'codpesAdmins' => env('SENHAUNICA_ADMINS'),

    /* TODO: DOCUMENTAR ESSE AQUI NO REPLICADO */
    'codUnd' => env('REPLICADO_CODUNDCLG'),

    /*
    |--------------------------------------------------------------------------
    |  Nº USP de um aluno ativo de Graduação da unidade
    |--------------------------------------------------------------------------
    |   Deve ser usado apenas em desenvolvimento
    */
    'codpesAluno' => env('CODPES_ALUNO'),

    /*
    |--------------------------------------------------------------------------
    |  Deve usar o pacote wsFoto?
    |--------------------------------------------------------------------------
    |   Valores: true | false
    |   Default: false
    */
    'wsFoto' => env('WSFOTO', false),

    /*
    |--------------------------------------------------------------------------
    |  Em produção ou desenvolvimento?
    |  Quando em produção, o valor deve ser prd e o CODPES_ALUNO deve ser vazio
    |  Quando em desenvolvimento, o valor deve ser dev e o CODPES_ALUNO deve estar preenchido
    |  com o nº USP de um aluno ativo da graduação para simular o link Créditos do Aluno
    |--------------------------------------------------------------------------
    |   Valores: dev | prd
    |   Default: dev
    */
    'envDev' => env('APP_ENV'),

    /*
    |--------------------------------------------------------------------------
    |  Quantidade mínina de caracteres a serem digitados na busca por parte do nome
    |--------------------------------------------------------------------------
    |   Valores: int
    |   Default: 5
    */
    'parteNomeLength' => env('PARTE_NOME_LENGTH'),
];
