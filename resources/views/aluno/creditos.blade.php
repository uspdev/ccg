@extends('layouts.app')

@section('title', config('app.name') . ' - Meus créditos')

@section('content')

  @can($gate)

    @include('flash')

	<h3>Meus créditos</h3>

	<div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab">Aluno</a></li>
        <li><a href="#tab_2" data-toggle="tab">Créditos</a></li>
        <li><a href="#tab_3" data-toggle="tab">Faltam</a></li>
        <li><a href="#tab_4" data-toggle="tab">Eletivas disponíveis</a></li>
        @can('secretaria')
          <li><a href="#tab_5" data-toggle="tab"><span class="fa fa-fw fa-file-pdf-o"></span>PDF</a></li>
        @endcan
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="box-primary">
            <div class="box-header">

              @if (config('ccg.wsFoto') === true)
                <img style="margin-left: 0px; margin-bottom: 10px;" class="profile-user-img img-responsive img-circle"
                  src="data: image/jpeg; base64, {{ Uspdev\Wsfoto::obter($dadosAcademicos->codpes) }}"
                  alt="{{ $dadosAcademicos->nompes }}" />
              @endif

              <h3 class="box-title">{{ $dadosAcademicos->codpes }} - {{ $dadosAcademicos->nompes }}</h3>
            </div>
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover" style="width: 50%;">
                <tr>
                  <th>Curso</th>
                  <td>{{ $dadosAcademicos->codcur }} - {{ $dadosAcademicos->nomcur }}</td>
                </tr>
                <tr>
                  <th>Habilitação</td>
                  <td>{{ $dadosAcademicos->codhab }} - {{ $dadosAcademicos->nomhab }}</td>
                </tr>
                <tr>
                  <th>Ano de ingresso</td>
                  <td>{{ Carbon\Carbon::parse($dadosAcademicos->dtainivin)->format('Y') }}</td>
                </tr>
                <tr>
                  <th>Programa</td>
                  <td>{{ $dadosAcademicos->codpgm }}</td>
                </tr>
                <tr>
                  <th colspan="2">
                    <table class="table table-hover table-striped">
                      <thead>
                        <tr>
                          <th style="border-bottom: 1px solid #000;">&nbsp;</th>
                          <th style="border-bottom: 1px solid #000;">Necessários</th>
                          <th style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Cursados</th>
                          <th style="border-bottom: 1px solid #000;">A concluir</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Créditos-aula em eletivas</td>
                          <td>{{ $curriculoAluno->numcredisoptelt }}</td>
                          <td style="border-right: 1px solid #000;">{{ $numcredisoptelt }}</td>
                          <td>
                            {{ $curriculoAluno->numcredisoptelt - $numcredisoptelt < 0 ? 0 : $curriculoAluno->numcredisoptelt - $numcredisoptelt }}
                          </td>
                        </tr>
                        <tr>
                          <td>Créditos totais em eletivas</td>
                          <td>{{ $curriculoAluno->numtotcredisoptelt }}</td>
                          <td style="border-right: 1px solid #000;">{{ $numtotcredisoptelt }}</td>
                          <td>
                            {{ $curriculoAluno->numtotcredisoptelt - $numtotcredisoptelt < 0 ? 0 : $curriculoAluno->numtotcredisoptelt - $numtotcredisoptelt }}
                          </td>
                        </tr>
                        <tr>
                          <td>Créditos-aula em livres</td>
                          <td>{{ $curriculoAluno->numcredisoptliv }}</td>
                          <td style="border-right: 1px solid #000;">{{ $numcredisoptliv }}</td>
                          <td>
                            {{ $curriculoAluno->numcredisoptliv - $numcredisoptliv < 0 ? 0 : $curriculoAluno->numcredisoptliv - $numcredisoptliv }}
                          </td>
                        </tr>
                        <tr>
                          <td>Créditos totais em livres</td>
                          <td>{{ $curriculoAluno->numtotcredisoptliv }}</td>
                          <td style="border-right: 1px solid #000;">{{ $numtotcredisoptliv }}</td>
                          <td>
                            {{ $curriculoAluno->numtotcredisoptliv - $numtotcredisoptliv < 0 ? 0 : $curriculoAluno->numtotcredisoptliv - $numtotcredisoptliv }}
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    </td>
                </tr>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane" id="tab_2">
          <div class="box-primary">
            <div class="box-header">
              <h3 class="box-title">{{ $dadosAcademicos->codpes }} - {{ $dadosAcademicos->nompes }}</h3>
            </div>
            <div class="box-body table-responsive">
              <h4>Disciplinas Concluídas</h4>
              <table style="width: 100%;" class="table table-bordered table-striped table-hover"
                id="disciplinasObrigatorias">
                <thead>
                  <tr>
                    <th><label>Diciplinas Obrigatórias</label></th>
                    <th>&nbsp;</th>
                  </tr>
                  <tr>
                    <th>Disciplinas</th>
                    <th>Créditos/Aula</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($disciplinasObrigatoriasConcluidas as $disciplinaConcluida)
                    @if (!in_array($disciplinaConcluida, $disciplinasOptativasLivresConcluidas) and
                        !in_array($disciplinaConcluida, $disciplinasOptativasEletivasConcluidas))
                      <tr>
                        <td style="width: 70%;">{{ $disciplinaConcluida }} -
                          {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinaConcluida) }}</td>
                        <td style="width: 30%;">{{ Uspdev\Replicado\Graduacao::creditosDisciplina($disciplinaConcluida) }}
                        </td>
                      </tr>
                    @endif
                  @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th style="text-align: right;">Total de créditos</th>
                    <th></th>
                  </tr>
                </tfoot>
              </table>
              <br />
              @can('secretaria')
                <form id="dispensas" role="form" method="post" action="dispensas">
                  {{ csrf_field() }}
                @endcan
                <table style="width: 100%;" class="table table-bordered table-striped table-hover"
                  id="disciplinasOptativasEletivas">
                  <thead>
                    <tr>
                      <th><label>Disciplinas Optativas Eletivas</label></th>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                    </tr>
                    <tr>
                      <th>
                        @can('secretaria')
                          Dispensa&nbsp;&nbsp;|&nbsp;&nbsp;
                        @endcan
                        Disciplinas</th>
                      <th>Créditos/Aula</th>
                      <th>Créditos/Trabalhos</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($disciplinasConcluidas as $disciplinaConcluida)
                      @if (in_array($disciplinaConcluida['coddis'], $disciplinasOptativasEletivasConcluidas))
                        <tr>
                          <td style="width: 70%;" id="td{{ $disciplinaConcluida['coddis'] }}">
                            @php
                              if (in_array($disciplinaConcluida['coddis'], $dispensas)) {
                                  $checked = 'checked';
                                  $creaul = 0;
                                  $cretrb = 0;
                              } else {
                                  $checked = '';
                                  $creaul = $disciplinaConcluida['creaul'];
                                  $cretrb = $disciplinaConcluida['cretrb'];
                              }
                            @endphp
                            @can('secretaria')
                              <input type="checkbox" name="coddis[]" id="input{{ $disciplinaConcluida['coddis'] }}"
                                title="Marque para dispensar o aluno desta disciplina"
                                value="{{ $disciplinaConcluida['coddis'] }}" {{ $checked }}>
                              &nbsp;&nbsp;
                            @endcan
                            {{ $disciplinaConcluida['coddis'] }} -
                            {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinaConcluida['coddis']) }}
                          </td>
                          <td style="width: 15%;">{{ $creaul }}</td>
                          <td style="width: 15%;">{{ $cretrb }}</td>
                        </tr>
                      @endif
                    @endforeach
                  </tbody>
                  <tfoot>
                    <tr>
                      <th style="text-align: right;">
                        @can('secretaria')
                          <input type="hidden" class="form-control" id="id_crl" name="id_crl"
                            value="{{ $curriculoAluno->id_crl }}">
                          <input type="hidden" class="form-control" id="codpes" name="codpes"
                            value="{{ $dadosAcademicos->codpes }}">
                          <button type="button" class="btn btn-primary"
                            onclick="document.getElementById('dispensas').submit();"
                            title="Recalcular os créditos considerando as dispensas">Recalcular</button>
                          &nbsp;&nbsp;
                          Total de créditos
                        @endcan
                      </th>
                      <th></th>
                      <th></th>
                    </tr>
                  </tfoot>
                </table>
                @can('secretaria')
                </form>
              @endcan
              <br />
              <table style="width: 100%;" class="table table-bordered table-striped table-hover"
                id="disciplinasLicenciaturas">
                <thead>
                  <tr>
                    <th><label>Disciplinas Licenciaturas</label></th>
                    <th>&nbsp;</th>
                  </tr>
                  <tr>
                    <th>Disciplinas</th>
                    <th>Créditos/Aula</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($disciplinasLicenciaturasConcluidas as $disciplinaConcluida)
                    @if (in_array($disciplinaConcluida, $disciplinasLicenciaturasConcluidas))
                      <tr>
                        <td style="width: 70%;">{{ $disciplinaConcluida }} -
                          {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinaConcluida) }}</td>
                        <td style="width: 30%;">{{ Uspdev\Replicado\Graduacao::creditosDisciplina($disciplinaConcluida) }}
                        </td>
                      </tr>
                    @endif
                  @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th style="text-align: right;">Total de créditos</th>
                    <th></th>
                  </tr>
                </tfoot>
              </table>
              <br />
              <table style="width: 100%;" class="table table-bordered table-striped table-hover"
                id="disciplinasOptativasLivres">
                <thead>
                  <tr>
                    <th><label>Disciplinas Optativas Livres</label></th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                  </tr>
                  <tr>
                    <th>Disciplinas</th>
                    <th>Créditos/Aula</th>
                    <th>Créditos/Trabalhos</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($disciplinasConcluidas as $disciplinaConcluida)
                    @if (in_array($disciplinaConcluida['coddis'], $disciplinasOptativasLivresConcluidas))
                      @if (App\Ccg\Aluno::getConcluiuEquivalente(
                          $disciplinaConcluida['coddis'],
                          $curriculoAluno->id_crl,
                          'Obrigatoria') ==
                          0 and
                          App\Ccg\Aluno::getConcluiuEquivalente($disciplinaConcluida['coddis'], $curriculoAluno->id_crl, 'Licenciatura') == 0)
                        <tr>
                          <td style="width: 70%;">{{ $disciplinaConcluida['coddis'] }} -
                            {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinaConcluida['coddis']) }}</td>
                          <td style="width: 15%;">{{ $disciplinaConcluida['creaul'] }}</td>
                          <td style="width: 15%;">{{ $disciplinaConcluida['cretrb'] }}</td>
                        </tr>
                      @endif
                    @endif
                  @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th style="text-align: right;">Total de créditos</th>
                    <th></th>
                    <th></th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane" id="tab_3">
          <div class="box-primary">
            <div class="box-header">
              <h3 class="box-title">{{ $dadosAcademicos->codpes }} - {{ $dadosAcademicos->nompes }}</h3>
            </div>
            <div class="box-body table-responsive">
              <h4>Disciplinas que Faltam</h4>
              <table style="width: 100%;" class="table table-bordered table-striped table-hover datatable">
                <thead>
                  <tr>
                    <th><label>Disciplinas Obrigatórias a concluir</label></th>
                  </tr>
                  <tr>
                    <th>Disciplinas</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($disciplinasObrigatoriasFaltam as $disciplinaObrigatoriaFalta)
                    <tr>
                      <td style="width: 100%;">{{ $disciplinaObrigatoriaFalta }} -
                        {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinaObrigatoriaFalta) }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <br />
              <table style="width: 100%;" class="table table-bordered table-striped table-hover datatable">
                <thead>
                  <tr>
                    <th><label>Disciplinas Licenciaturas a concluir</label></th>
                  </tr>
                  <tr>
                    <th>Disciplinas</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($disciplinasLicenciaturasFaltam as $disciplinaLicenciaturaFalta)
                    <tr>
                      <td style="width: 100%;">{{ $disciplinaLicenciaturaFalta }} -
                        {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinaLicenciaturaFalta) }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane" id="tab_4">
          <div class="box-primary">
            <div class="box-header">
              <h3 class="box-title">{{ $dadosAcademicos->codpes }} - {{ $dadosAcademicos->nompes }}</h3>
            </div>
            <div class="box-body table-responsive">
              <h4>Disciplinas Optativas Eletivas disponíveis</h4>
              <table style="width: 100%;" class="table table-bordered table-striped table-hover datatable">
                <thead>
                  <tr>
                    <th>Disciplinas</th>
                    <th>Créditos/Aula</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($disciplinasOptativasEletivasFaltam as $disciplinaOptativaEletivaFalta)
                    <tr>
                      <td style="width: 100%;">{{ $disciplinaOptativaEletivaFalta }} -
                        {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinaOptativaEletivaFalta) }}</td>
                      <td>
                        {{ Uspdev\Replicado\Graduacao::creditosDisciplina($disciplinaOptativaEletivaFalta) }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane" id="tab_5">
          <div class="box-primary">
            <div class="box-header">
              <h3 class="box-title">{{ $dadosAcademicos->codpes }} - {{ $dadosAcademicos->nompes }}</h3>
            </div>
            <div class="box-body table-responsive">
              <form id="observacoes" role="form" method="POST" action="creditos">
                {{ csrf_field() }}
                <div class="form-group">
                  <label>Observações do Currículo</label>
                  <textarea id="txtobscur" name="txtobscur" class="form-control" rows="3" maxlength="500"
                    placeholder="Digite aqui" disabled>{{ $curriculoAluno->txtobs }}</textarea>
                </div>
                <div class="form-group">
                  <label>Observações do Aluno</label>
                  @if (isset(App\Models\AlunosObservacoes::where(['id_crl' => $curriculoAluno->id_crl, 'codpes' => $dadosAcademicos->codpes])->first()->txtobs))
                    <textarea id="txtobs" name="txtobs" class="form-control" rows="3" placeholder="Digite aqui">{{ App\Models\AlunosObservacoes::where([
                        'id_crl' => $curriculoAluno->id_crl,
                        'codpes' => $dadosAcademicos->codpes,
                    ])->first()->txtobs }}</textarea>
                  @else
                    <textarea id="txtobs" name="txtobs" class="form-control" rows="3" maxlength="500"
                      placeholder="Digite aqui"></textarea>
                  @endif
                  <input type="hidden" class="form-control" id="id_crl" name="id_crl"
                    value="{{ $curriculoAluno->id_crl }}">
                  <input type="hidden" class="form-control" id="codpes" name="codpes"
                    value="{{ $dadosAcademicos->codpes }}">
                </div>
                <button type="button" class="btn btn-primary btn-sm"
                  onclick="document.getElementById('observacoes').submit();">
                  Gerar PDF <span class="fa fa-fw fa-file-pdf-o"></span></button>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>

  @endcan

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

      // Total de créditos
      $('#disciplinasObrigatorias').dataTable({
        "footerCallback": function(tfoot, data, start, end, display) {
          var api = this.api();
          $(api.column(1).footer()).html(
            api.column(1).data().reduce(function(a, b) {
              a = parseInt(a);
              b = parseInt(b);
              return a + b;
            }, 0)
          );
        },
        language: {
          url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json'
        },
        lengthMenu: [
          [10, 25, 50, 100, -1],
          ['10 linhas', '25 linhas', '50 linhas', '100 linhas', 'Mostar todos']
        ],
        pageLength: -1
      });

      $('#disciplinasOptativasLivres').dataTable({
        "footerCallback": function(tfoot, data, start, end, display) {
          var api = this.api();
          $(api.column(1).footer()).html(
            api.column(1).data().reduce(function(a, b) {
              a = parseInt(a);
              b = parseInt(b);
              return a + b;
            }, 0)
          );
          $(api.column(2).footer()).html(
            api.column(2).data().reduce(function(a, b) {
              a = parseInt(a);
              b = parseInt(b);
              return a + b;
            }, 0)
          );
        },
        language: {
          url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json'
        },
        lengthMenu: [
          [10, 25, 50, 100, -1],
          ['10 linhas', '25 linhas', '50 linhas', '100 linhas', 'Mostar todos']
        ],
        pageLength: -1
      });

      $('#disciplinasOptativasEletivas').dataTable({
        "footerCallback": function(tfoot, data, start, end, display) {
          var api = this.api();
          $(api.column(1).footer()).html(
            api.column(1).data().reduce(function(a, b) {
              a = parseInt(a);
              b = parseInt(b);
              return a + b;
            }, 0)
          );
          $(api.column(2).footer()).html(
            api.column(2).data().reduce(function(a, b) {
              a = parseInt(a);
              b = parseInt(b);
              return a + b;
            }, 0)
          );
        },
        language: {
          url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json'
        },
        lengthMenu: [
          [10, 25, 50, 100, -1],
          ['10 linhas', '25 linhas', '50 linhas', '100 linhas', 'Mostar todos']
        ],
        pageLength: -1
      });

      $('#disciplinasLicenciaturas').dataTable({
        "footerCallback": function(tfoot, data, start, end, display) {
          var api = this.api();
          $(api.column(1).footer()).html(
            api.column(1).data().reduce(function(a, b) {
              a = parseInt(a);
              b = parseInt(b);
              return a + b;
            }, 0)
          );
        },
        language: {
          url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json'
        },
        lengthMenu: [
          [10, 25, 50, 100, -1],
          ['10 linhas', '25 linhas', '50 linhas', '100 linhas', 'Mostar todos']
        ],
        pageLength: -1
      });

    });
  </script>

@stop
