@extends('layouts.app')

@section('title', config('app.name') . ' - Currículos - Editar Currículo ' . $curriculo['id'])

@section('content')

  @include('flash')

  <h3>Editar Currículo {{ $curriculo['id'] }}</h3>

  <div class="box box-primary">
    <form role="form" method="POST" action="curriculos/{{ $curriculo['id'] }}">
      {{ csrf_field() }}
      {{ method_field('patch') }}
      <div class="box-body">
        @include('curriculos.partials.form-select-curso')
        @include('curriculos.partials.form-select-habilitacao')

        <div class="form-group">
          <label for="numcredisoptelt">Nº de créditos (aula) exigidos em displinas optativas eletivas</label>
          <input class="form-control" id="numcredisoptelt" name="numcredisoptelt" pattern="\d*"
            value="{{ $curriculo['numcredisoptelt'] }}"
            placeholder="Nº de créditos (aula) exigidos em displinas optativas eletivas" type="text" required>
        </div>
        <div class="form-group">
          <label for="numtotcredisoptelt">Nº de créditos totais (aula + trabalho) exigidos em displinas optativas
            eletivas</label>
          <input class="form-control" id="numtotcredisoptelt" name="numtotcredisoptelt" pattern="\d*"
            value="{{ $curriculo['numtotcredisoptelt'] }}"
            placeholder="Nº de créditos totais (aula + trabalho) exigidos em displinas optativas eletivas" type="text"
            required>
        </div>
        <div class="form-group">
          <label for="numcredisoptliv">Nº de créditos (aula) exigidos em displinas optativas livres</label>
          <input class="form-control" id="numcredisoptliv" name="numcredisoptliv" pattern="\d*"
            value="{{ $curriculo['numcredisoptliv'] }}"
            placeholder="Nº de créditos (aula) exigidos em displinas optativas livres" type="text" required>
        </div>
        <div class="form-group">
          <label for="numtotcredisoptliv">Nº de créditos totais (aula + trabalho) exigidos em displinas optativas
            livres</label>
          <input class="form-control" id="numtotcredisoptliv" name="numtotcredisoptliv" pattern="\d*"
            value="{{ $curriculo['numtotcredisoptliv'] }}"
            placeholder="Nº de créditos totais (aula + trabalho) exigidos em displinas optativas livres" type="text"
            required>
        </div>
        <div class="form-group">
          <label for="dtainicrl">Ano de ingresso</label>
          <div class="input-group">
            <div class="input-group-addon">
              <i class="glyphicon glyphicon-calendar"></i>
            </div>
            <input id="dtainicrl" name="dtainicrl" class="form-control"
              value="{{ Carbon\Carbon::parse($curriculo['dtainicrl'])->format('d/m/Y') }}" {{-- data-inputmask="'alias': 'dd/mm/yyyy'" data-mask=""  --}}
              type="text" required>
          </div>
        </div>
        <div class="form-group">
          <label>Observações</label>
          <textarea id="txtobs" name="txtobs" class="form-control" rows="3" placeholder="Digite aqui">{{ $curriculo['txtobs'] }}</textarea>
        </div>
      </div>
      <div class="box-footer">
        <button type="submit" class="btn btn-primary btn-sm">Salvar</button>
        <button type="button" class="btn btn-secondary btn-sm"
          onclick="location.href='curriculos/{{ $curriculo['id'] }}'">Cancelar</button>
      </div>
    </form>

  </div>

@stop

@section('javascripts_bottom')
  @parent

  <script type="text/javascript">
    $(function() {

      //Datepicker
      $('.datepicker').datepicker({
        format: "dd/mm/yyyy",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true
      });

    });
  </script>

@stop
