## Conceito:

Ferramenta para auxiliar na verificação das disciplinas cursadas pelos alunos e no levantamento das pendências para a conclusão do seu curso. A ferramenta deve ser acessada pelo navegador com acesso restrito aos servidores do Serviço de Graduação, consultar o resumo escolar dos alunos pela replicação do Júpiter e fazer a comparação com um currículo previamente cadastrado.

## Justificativa:

O sistema Júpiter não foi concebido para verificar as alterações que ocorrem dentro do mesmo currículo para anos diferentes de ingresso.

## Tela inicial:

A ferramenta deve extrair dos dados do Júpiter de duas formas: individual, através do nº USP ou, por grupo de alunos, através da informação do ano de ingresso, curso e habilitação desejados. 

## Dados Júpiter:

A Ferramenta deve coletar da replicação do Júpiter:

- Nº USP
- Nome
- Curso
- Habilitação
- Ano de ingresso
- Disciplinas com aprovação, com o respectivo número de créditos aula e trabalho.


## Currículo:

Esses dados devem ser comparados com o currículo previamente cadastrado na ferramenta pelo Serviço de Graduação, que conterá:

- Curso
- Habilitação
- Ano de ingresso
- Disciplinas obrigatórias
- Disciplinas optativas eletivas
- Disciplinas da Faculdade de Educação (para cursos de licenciatura)
- Nº de créditos-aula exigidos em disciplinas optativas eletivas
- Nº de créditos-aula exigidos em disciplinas optativas livres

## Model

<img src="docs/model.jpg" width="80%" />

## Procedimentos de deploy
 
    - composer install
    - cp .env.example .env
    - Editar o arquivo .env
        - Dados da conexão na base replicada
        - Código da unidade local
    - php artisan key:generate
    - php artisan migrate

Caso falte alguma dependência, siga as instruções do `composer`.

## Projetos utilizados

    - uspdev/replicado

## Contribuindo com o projeto

### Passos iniciais

Siga o guia no site do [uspdev](https://uspdev.github.io/contribua)

### Padrões de Projeto

Utilizamos a [PSR-2](https://www.php-fig.org/psr/psr-2/) para padrões de projeto. Ajuste seu editor favorito para a especificação.