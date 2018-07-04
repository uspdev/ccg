@extends('adminlte::page')

@section('title', env('APP_NAME') . ' - Meus créditos')

@section('content_header')
    <h1>Meus créditos</h1>
@stop

@section('content')

    @can($gate)

    @include('flash')

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab">Aluno</a></li>
            <li><a href="#tab_2" data-toggle="tab">Créditos</a></li>
            <li><a href="#tab_3" data-toggle="tab">Faltam</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
          		<div class="box-primary">
            		<div class="box-header">
						<img style="margin-left: 0px; margin-bottom: 10px;" class="profile-user-img img-responsive img-circle" 
                            src="data: image/jpeg; base64, {{ Uspdev\Wsfoto::obter($graduacaoCurso['codpes']) }}" alt="{{ $graduacaoCurso['nompes'] }}" />
              			<h3 class="box-title">{{ $graduacaoCurso['codpes'] }} - {{ $graduacaoCurso['nompes'] }}</h3>
            		</div>
            		<div class="box-body table-responsive no-padding">
              			<table class="table table-hover">
                			<tr>
                  				<th>Curso</th>
                  				<td>{{ $graduacaoCurso['codcur'] }} - {{ $graduacaoCurso['nomcur'] }}</td>
                			</tr>
                			<tr>
                  				<th>Habilitação</td>
                  				<td>{{ $graduacaoCurso['codhab'] }} - {{ $graduacaoCurso['nomhab'] }}</td>
                			</tr>
                			<tr>
                  				<th>Ano de ingresso</td>
                  				<td>{{ Carbon\Carbon::parse($graduacaoCurso['dtainivin'])->format('Y') }}</td>
                			</tr>
              			</table>
            		</div>
          		</div>
            </div>
              
            <div class="tab-pane" id="tab_2">
          		<div class="box-primary">
            		<div class="box-header">
              			<h3 class="box-title">{{ $graduacaoCurso['codpes'] }} - {{ $graduacaoCurso['nompes'] }}</h3>
            		</div>
          		</div>
            </div>
              
            <div class="tab-pane" id="tab_3">
          		<div class="box-primary">
            		<div class="box-header">
              			<h3 class="box-title">{{ $graduacaoCurso['codpes'] }} - {{ $graduacaoCurso['nompes'] }}</h3>
            		</div>
          		</div>
            </div>
        </div>
    </div>

    @endcan

@stop
