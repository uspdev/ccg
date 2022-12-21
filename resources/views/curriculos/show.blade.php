@extends('layouts.app')

@section('title', config('app.name') . ' - Currículo ' . $curriculo['id'])

@section('content')

  @include('flash')
  <h3>Currículo {{ $curriculo['id'] }}</h3>

  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="nav-item active">
        <a class="nav-link" href="#tab_1" data-toggle="tab">Currículo</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#tab_2" data-toggle="tab"
          onclick="location.href='curriculos/{{ $curriculo['id'] }}/alunos';">Alunos</a>
      </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="tab_1">
        <div class="box-primary">
          <div class="box-body table-responsive no-padding">
            <table class="table">
              <tr>
                <th style="width: 30%;">Curso</th>
                <td style="width: 70%;">{{ $curriculo['codcur'] }} -
                  {{ Uspdev\Replicado\Graduacao::nomeCurso($curriculo['codcur']) }}</td>
              </tr>
              <tr>
                <th>Habilitação</td>
                <td>{{ $curriculo['codhab'] }} -
                  {{ Uspdev\Replicado\Graduacao::nomeHabilitacao($curriculo['codhab'], $curriculo['codcur']) }}</td>
              </tr>
              <tr>
                <th>Nº de créditos (aula) exigidos em displinas optativas eletivas</td>
                <td>{{ $curriculo['numcredisoptelt'] }}</td>
              </tr>
              <tr>
                <th>Nº de créditos totais (aula + trabalho) exigidos em displinas optativas eletivas</td>
                <td>{{ $curriculo['numtotcredisoptelt'] }}</td>
              </tr>
              <tr>
                <th>Nº de créditos (aula) exigidos em displinas optativas livres</td>
                <td>{{ $curriculo['numcredisoptliv'] }}</td>
              </tr>
              <tr>
                <th>Nº de créditos totais (aula + trabalho) exigidos em displinas optativas livres</td>
                <td>{{ $curriculo['numtotcredisoptliv'] }}</td>
              </tr>
              <tr>
                <th>Ano de ingresso</td>
                <td>{{ Carbon\Carbon::parse($curriculo['dtainicrl'])->format('Y') }}</td>
              </tr>
              <tr>
                <th>Observações</td>
                <td>{!! nl2br(e($curriculo['txtobs'])) !!}</td>
              </tr>
            </table>
            <div class="box-body table-responsive">
              <button type="button" class="btn btn-primary btn-sm" title="Editar"
                onclick="location.href='curriculos/{{ $curriculo['id'] }}/edit';">
                <i class="far fa-edit"></i>&nbsp;&nbsp;&nbsp;Editar Currículo
              </button>
              <button type="button" class="btn btn-success btn-sm" title="Grade Curricular atual (Jupiter)"
                onclick="location.href='curriculos/{{ $curriculo['id'] }}/gradeCurricularJupiter';">
                <i class="fas fa-plus"></i> Importar Grade Curricular (Jupiter)
              </button>
              <button type="button" class="btn btn-success btn-sm" title="Adicionar Disciplina Obrigatória"
                onclick="location.href='disciplinasObrigatorias/create/{{ $curriculo['id'] }}';">
                <i class="fas fa-plus"></i>&nbsp;Adicionar Disciplina Obrigatória
              </button>
              <button type="button" class="btn btn-success btn-sm" title="Adicionar Disciplina Optativa Eletiva"
                onclick="location.href='disciplinasOptativasEletivas/create/{{ $curriculo['id'] }}';">
                <i class="fas fa-plus"></i>&nbsp;Adicionar Disciplina Optativa Eletiva
              </button>
              <button type="button" class="btn btn-success btn-sm"
                title="Adicionar Disciplina Licenciaturas (Faculdade de Educação)"
                onclick="location.href='disciplinasLicenciaturas/create/{{ $curriculo['id'] }}';">
                <i class="fas fa-plus"></i>&nbsp;Adicionar Disciplina Licenciaturas (Faculdade de
                Educação)
              </button>
            </div>
            @include('curriculos.partials.disciplinas-obrigatorias')
            <br />
            @include('curriculos.partials.disciplinas-optativas-eletivas')
            <br />
            @include('curriculos.partials.disciplinas-licenciaturas')
          </div>
        </div>
      </div>
      <div class="tab-pane" id="tab_2">
        <div class="box-primary">
          <table class="table">
            <tr>
              <th style="width: 30%;">Curso</th>
              <td style="width: 70%;">{{ $curriculo['codcur'] }} -
                {{ Uspdev\Replicado\Graduacao::nomeCurso($curriculo['codcur']) }}</td>
            </tr>
            <tr>
              <th>Habilitação</td>
              <td>{{ $curriculo['codhab'] }} -
                {{ Uspdev\Replicado\Graduacao::nomeHabilitacao($curriculo['codhab'], $curriculo['codcur']) }}</td>
            </tr>
            <tr>
              <th>Nº de créditos (aula) exigidos em displinas optativas eletivas</td>
              <td>{{ $curriculo['numcredisoptelt'] }}</td>
            </tr>
            <tr>
              <th>Nº de créditos (aula) exigidos em displinas optativas livres</td>
              <td>{{ $curriculo['numcredisoptliv'] }}</td>
            </tr>
            <tr>
              <th>Ano de ingresso</td>
              <td>{{ Carbon\Carbon::parse($curriculo['dtainicrl'])->format('Y') }}</td>
            </tr>
          </table>
          <br />
          <table class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
            <thead>
              <tr>
                <th style="width: 70%;">Nome</th>
                <th style="width: 30%;">Ações</th>
              </tr>
            </thead>
            <tbody>
              @isset($alunosCurriculo)
                @foreach ($alunosCurriculo as $aluno)
                  <tr>
                    <td>{{ $aluno['nompes'] }}</td>
                    <td>
                      <button type="button" class="btn btn-success btn-xs" title="Analisar Currículo"
                        onclick="location.href='creditos/{{ $aluno['codpes'] }}';">
                        <span class="fas fa-check"></span>
                      </button>
                    </td>
                  </tr>
                @endforeach
              @endisset
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

@stop

@section('javascripts_bottom')
@parent

  <script type="text/javascript">
    $(function() {
      //Initialize Select2 Elements
      $('.select2').select2({
        placeholder: "Selecione",
        allowClear: true
      });

      //Datepicker
      $('.datepicker').datepicker({
        format: "dd/mm/yyyy",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true
      });

      // // DataTables
      // $('.datatable').DataTable({
      //     // language    	: {
      //     //     url     : '//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json'
      //     // },  
      //     paging      	: true,
      //     lengthChange	: true,
      //     searching   	: true,
      //     ordering    	: true,
      //     info        	: true,
      //     autoWidth   	: true,
      //     lengthMenu		: [
      // 		[ 10, 25, 50, 100, -1 ],
      // 		[ '10 linhas', '25 linhas', '50 linhas', '100 linhas', 'Mostar todos' ]
      // 	],
      // 	pageLength  	: -1
      // });

    });

    @if (substr(request()->path(), -6, 6) == 'alunos')
      /* Active tab-pane */
      $(document).ready(function() {
        activaTab('tab_2');
      });
    @else
      $(document).ready(function() {
        activaTab('tab_1');
      });
    @endif

    function activaTab(tab) {
      $('.nav-tabs a[href="#' + tab + '"]').tab('show');
    };
  </script>

@stop
