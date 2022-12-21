@extends('adminlte::page')

@section('title', config('app.name') . ' - Currículo ' . $curriculo['id'] . ' - Disciplinas Obrigatórias Equivalentes -
  ' . $disciplinasObrigatoria['coddis'])

@section('content')

  @include('flash')
  <h3>Curriculo {{ $curriculo['id'] }} - Adicionar Disciplinas Obrigatórias Equivalentes -
    {{ $disciplinasObrigatoria['coddis'] }}</h3>

  <div class="box box-primary">
    <form role="form" method="POST" action="disciplinasObrEquivalentes/create/{{ $disciplinasObrigatoria['id'] }}">

      {{ csrf_field() }}

      <div class="box-body">
        <table class="table">
          <tr>
            <th>Curso</th>
            <td>{{ $curriculo['codcur'] }} - {{ Uspdev\Replicado\Graduacao::nomeCurso($curriculo['codcur']) }}</td>
          </tr>
          <tr>
            <th>Habilitação</td>
            <td>{{ $curriculo['codhab'] }} -
              {{ Uspdev\Replicado\Graduacao::nomeHabilitacao($curriculo['codhab'], $curriculo['codcur']) }}</td>
          </tr>
          <tr>
            <th>Ano de ingresso</td>
            <td>{{ Carbon\Carbon::parse($curriculo['dtainicrl'])->format('Y') }}</td>
          </tr>
        </table>

        <table class="table table-bordered table-striped table-hover datatable">
          <thead>
            <tr>
              <th>
                <div class="form-group">
                  <label>Adicionar Disciplina Obrigatória Equivalente -
                    {{ $disciplinasObrigatoria['coddis'] }} -
                    {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinasObrigatoria['coddis']) }}</label>
                  <select class="form-control select2" style="width: 100%;" id="coddisobreqv" name="coddisobreqv" required>
                    <option></option>
                    @foreach ($disciplinas as $disciplina)
                      <option value="{{ $disciplina['coddis'] }}">{{ $disciplina['coddis'] }} -
                        {{ $disciplina['nomdis'] }}</option>
                    @endforeach
                  </select><br /><br />
                  <label>Equivalência</label><br />
                  <input type="radio" name="tipeqv" id="tipeqv" value="E" required>&nbsp;&nbsp;E<br />
                  <input type="radio" name="tipeqv" id="tipeqv" value="OU">&nbsp;&nbsp;OU
                </div>
              </th>
            </tr>
            <tr>
              <th><label>Diciplinas Obrigatórias Equivalentes</label></th>
            </tr>
            <tr>
              <th>Disciplinas</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($disciplinasObrigatoriasEquivalentes as $disciplinasObrigatoriasEquivalente)
              <tr>
                <td>{{ $disciplinasObrigatoriasEquivalente['coddis'] }} -
                  {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinasObrigatoriasEquivalente['coddis']) }}
                  &nbsp;&nbsp;<strong>{{ $disciplinasObrigatoriasEquivalente['tipeqv'] }}</strong>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="box-footer">
        <button type="submit" class="btn btn-primary btn-sm">Salvar</button>
        <button type="button" class="btn btn-info btn-sm" onclick='location.href="curriculos/{{ $curriculo->id }}";'
          title="Ver Currículo">
          <span class="far fa-eye"></span>&nbsp;&nbsp;&nbsp;Ver Currículo
        </button>
      </div>
    </form>
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
      $('.datatable').DataTable({
        language: {
          url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json'
        },
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: true,
        lengthMenu: [
          [10, 25, 50, 100, -1],
          ['10 linhas', '25 linhas', '50 linhas', '100 linhas', 'Mostar todos']
        ],
        pageLength: -1
      });
    })
  </script>

@stop
