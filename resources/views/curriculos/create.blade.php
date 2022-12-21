@extends('layouts.app')

@section('title', config('app.name') . ' - Currículos - Adicionar Currículo')

@section('content')

  @include('flash')

  <h3>Adicionar Currículo</h3>

  <div class="box box-primary">
    <form role="form" method="POST" action="curriculos">

      {{ csrf_field() }}

      <div class="box-body">
        @include('curriculos.partials.form-select-curso')
        @include('curriculos.partials.form-select-habilitacao')

        <div class="form-group">
          <label for="numcredisoptelt">Nº de créditos (aula) exigidos em displinas optativas eletivas</label>
          <input class="form-control" id="numcredisoptelt" name="numcredisoptelt" pattern="\d*"
            value="{{ $curriculo['numcredisoptelt'] ?? '' }}"
            placeholder="Nº de créditos (aula) exigidos em displinas optativas eletivas" type="text" required>
        </div>

        <div class="form-group">
          <label for="numtotcredisoptelt">Nº de créditos totais (aula + trabalho) exigidos em displinas optativas
            eletivas</label>
          <input class="form-control" id="numtotcredisoptelt" name="numtotcredisoptelt" pattern="\d*"
            placeholder="Nº de créditos totais (aula + trabalho) exigidos em displinas optativas eletivas" type="text"
            required>
        </div>
        <div class="form-group">
          <label for="numcredisoptliv">Nº de créditos (aula) exigidos em displinas optativas livres</label>
          <input class="form-control" id="numcredisoptliv" name="numcredisoptliv" pattern="\d*"
            placeholder="Nº de créditos (aula) exigidos em displinas optativas livres" type="text" required>
        </div>
        <div class="form-group">
          <label for="numtotcredisoptliv">Nº de créditos totais (aula + trabalho) exigidos em displinas optativas
            livres</label>
          <input class="form-control" id="numtotcredisoptliv" name="numtotcredisoptliv" pattern="\d*"
            placeholder="Nº de créditos totais (aula + trabalho) exigidos em displinas optativas livres" type="text"
            required>
        </div>
        <div class="form-group">
          <label for="dtainicrl">Ano de ingresso</label>
          <div class="input-group">
            <div class="input-group-addon">
              <i class="glyphicon glyphicon-calendar"></i>
            </div>
            <input id="dtainicrl" name="dtainicrl" class="form-control " type="text" required>
          </div>
        </div>
        <div class="form-group">
          <label>Observações</label>
          <textarea id="txtobs" name="txtobs" class="form-control" rows="3" placeholder="Digite aqui"></textarea>
        </div>
      </div>
      <div class="box-footer">
        <button type="submit" class="btn btn-primary">Salvar</button>
      </div>
    </form>
  </div>

@endsection
