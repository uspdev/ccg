@extends('adminlte::page')

@section('title', env('APP_NAME') . ' - Meus créditos')

@section('content_header')
    <h1>Meus créditos</h1>
@stop

@section('content')

    @can('secretaria') {{-- #desenvolvimento  --}}
    {{-- @can('alunos') --}} {{-- #produção  --}}

        @include('flash')

        <h3>{{ $graduacaoCurso['codpes'] }} - {{ $graduacaoCurso['nompes'] }}</h3>
        Curso: <strong>{{ $graduacaoCurso['codcur'] }} - {{ $graduacaoCurso['nomcur'] }}</strong><br />
        Habilitação: <strong>{{ $graduacaoCurso['codhab'] }} - {{ $graduacaoCurso['nomhab'] }}</strong><br />
        Ano de ingresso: <strong>{{ Carbon\Carbon::parse($graduacaoCurso['dtainivin'])->format('Y') }}</strong>

    @endcan

@stop
