@extends('adminlte::page')

@section('title', env('APP_NAME') . ' - Buscar aluno')

@section('content_header')
    <h1>Buscar aluno</h1>
@stop

@section('content')

    @include('flash')

    <div class="box box-primary">
        <form id="busca" role="form" method="post" action"/buscaReplicado">
            {{ csrf_field() }} 
            <div class="box-body">
                <div class="form-group">
                    <label for="codpes">Nº USP</label>
                    <input type="text" class="form-control" id="codpes" name="codpes" placeholder="Nº USP" pattern="\d+" required>
                </div>
                <div class="form-group">
                    <label for="parteNome">Nome</label>
                    <input type="text" class="form-control" id="parteNome" name="parteNome" placeholder="Parte do Nome">
                    <table class="table table-striped table-hover"><tbody id="alunos"></tbody></table>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>
    </div>

    @if (isset($graduacaoCurso))

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
                			<tr>
                  				<th>Programa</td>
                  				<td>{{ $graduacaoPrograma['codpgm'] }}</td>
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

    @endif

@stop

@section('js')

    <script type="text/javascript">
        $(document).ready(function() {
                $(window).keydown(function(event){
                if((event.keyCode == 13)) {
                    event.preventDefault();
                    return false;
                }
            });
        });
        
        $('#parteNome').on('keypress', function() {
            if ($('#parteNome').val().length >= 3) {
                $.get("busca/" + $('#parteNome').val(), function(data) {
                	$('#alunos').empty();
                	$.each(data, function(i, value) {
                    	var tr = $("<tr title='Clique para ver as informações de " + value.nompes + "' onclick=$('#codpes').val(" + value.codpes + ");$('#alunos').empty();$('#busca').submit(); />");
                        	tr.append($("<td/>", {
                            	text : value.nompes
                        	}))
                    	$('#alunos').append(tr);
                	});
            	}); 
            }
        });
    </script>

@stop
