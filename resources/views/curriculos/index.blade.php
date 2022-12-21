@extends('layouts.app')

@section('title', config('app.name') . ' - Currículos')

@section('content')

  @include('flash')

  <h3>Currículos</h3>

  <div class="box box-primary">
    <div class="box-body">
      <table class="table table-bordered table-striped table-hover datatable">
        <thead>
          <tr>
            <th colspan="4">
              <button type="button" class="btn btn-success btn-sm" title="Adicionar Currículo"
                onclick="location.href='curriculos/create';">
                <i class="fas fa-plus"></i> Adicionar Currículo
              </button>
            </th>
          </tr>
          <tr>
            <th colspan="4">
              Listando Currículos &nbsp;&nbsp;&nbsp;
              <span class="badge bg-yellow">{{ substr($ano, 0, 4) }}</span>
              @foreach ($anos as $a)
                @if (substr($ano, 0, 4) != substr($a->dtainicrl, 0, 4))
                  | <a href="curriculos?ano={{ substr($a->dtainicrl, 0, 4) }}"
                    title="Listar currículos no ano de {{ substr($a->dtainicrl, 0, 4) }}">
                    {{ substr($a->dtainicrl, 0, 4) }}</a>
                @endif
              @endforeach
              | <a href="curriculos?ano=Tudo" title="Lista todos os currículos">
                Tudo</a> (pode demorar a carregar a página)
            </th>
          </tr>
          <tr>
            <th>Curso</th>
            <th>Habilitação</th>
            <th>Ingresso</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>

          @foreach ($curriculos as $curriculo)
            <tr>
              <td>{{ $curriculo->codcur }} - {{ Uspdev\Replicado\Graduacao::nomeCurso($curriculo->codcur) }}</td>
              <td>
                {{ $curriculo->codhab }} -
                {{ Uspdev\Replicado\Graduacao::nomeHabilitacao($curriculo->codhab, $curriculo->codcur) }}
              </td>
              <td>{{ Carbon\Carbon::parse($curriculo->dtainicrl)->format('Y') }}</td>
              <td class="">
                @include('curriculos.partials.btn-ver-curriculo')
                @include('curriculos.partials.btn-impressao')
                @include('curriculos.partials.btn-editar')
                @include('curriculos.partials.btn-copiar')
                @include('curriculos.partials.btn-analizar')
                @include('curriculos.partials.btn-alunos')
                @include('curriculos.partials.btn-apagar')
              </td>
            </tr>
          @endforeach

        </tbody>
      </table>
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

      // DataTables
      // $('.datatable').DataTable({
      //   language: {
      //     url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json'
      //   },
      //   paging: true,
      //   lengthChange: true,
      //   searching: true,
      //   ordering: true,
      //   info: true,
      //   autoWidth: true,
      //   lengthMenu: [
      //     [10, 25, 50, 100, -1],
      //     ['10 linhas', '25 linhas', '50 linhas', '100 linhas', 'Mostar todos']
      //   ],
      //   pageLength: -1
      // });
    })
  </script>

@stop
