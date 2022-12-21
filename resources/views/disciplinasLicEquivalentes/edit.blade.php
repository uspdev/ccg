@extends('adminlte::page')

@section('title', config('app.name') . ' - Currículo ' . $curriculo['id'] . ' - Disciplinas Licenciaturas Equivalentes -
  ' . $disciplinaLicenciatura[0]['coddis'])

@section('content')

  @include('flash')

  <h3>Curriculo {{ $curriculo['id'] }} - Editar Disciplina Licenciatura Equivalente -
    {{ $disciplinaLicenciatura[0]['coddis'] }}</h3>

  <div class="box box-primary">
    <form role="form" method="POST"
      action="disciplinasLicEquivalentes/{{ $disciplinaLicenciaturaEquivalente[0]['id'] }}">

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
                  <label>{{ $disciplinaLicenciaturaEquivalente[0]['coddis'] }} -
                    {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinaLicenciaturaEquivalente[0]['coddis']) }}</label>
                  <label>Equivalência</label><br />
                  @if ($disciplinaLicenciaturaEquivalente[0]['tipeqv'] == 'OU')
                    <input type="radio" name="tipeqv" id="tipeqv" value="E" required>&nbsp;&nbsp;E<br />
                    <input type="radio" name="tipeqv" id="tipeqv" value="OU" checked>&nbsp;&nbsp;OU
                  @else
                    <input type="radio" name="tipeqv" id="tipeqv" value="E" required
                      checked>&nbsp;&nbsp;E<br />
                    <input type="radio" name="tipeqv" id="tipeqv" value="OU">&nbsp;&nbsp;OU
                  @endif
                  <input type="hidden" name="id_dis_lic" id="id_dis_lic" value="{{ $disciplinaLicenciatura[0]['id'] }}">
                  <input type="hidden" name="coddis" id="coddis"
                    value="{{ $disciplinaLicenciaturaEquivalente[0]['coddis'] }}">
                </div>
              </th>
            </tr>
          </thead>
          <tbody>
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
  </script>

@stop
