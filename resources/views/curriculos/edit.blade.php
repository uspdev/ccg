@extends('adminlte::page')

@section('title', env('APP_NAME') . ' - Currículos - Editar Currículo ' . $curriculo['id'])

@section('content_header')
    <h1>Editar Currículo {{ $curriculo['id'] }}</h1>
@stop

@section('content')

    @include('flash')

    <div class="box box-primary">
        <form role="form" method="POST" action="/curriculos/{{ $curriculo['id'] }}">

            {{ csrf_field() }}
            {{ method_field('patch') }}

            <div class="box-body">
                <div class="form-group">
                    <label>Curso</label>
                    <select class="form-control select2" style="width: 100%;" id="codcur" name="codcur" required>                   
                        @foreach ($cursos as $curso)
                            @if ($curso['codcur'] === $curriculo['codcur'])
                                <option value="{{ $curso['codcur'] }}" selected>{{ $curso['codcur'] }} - {{ $curso['nomcur'] }}</option>
                            @else 
                                <option value="{{ $curso['codcur'] }}">{{ $curso['codcur'] }} - {{ $curso['nomcur'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>              
                <div class="form-group">
                    <label>Habilitação</label>
                    <select class="form-control select2" style="width: 100%;" id="codhab" name="codhab" required>
                        @foreach ($habilitacoes as $habilitacao)
                            @if ($habilitacao['codhab'] === $curriculo['codhab'] and $habilitacao['codcur'] === $curriculo['codcur'])
                                <option value="{{ $habilitacao['codhab'] }}" selected>{{ $habilitacao['codhab'] }} - {{ $habilitacao['nomhab'] }}</option>
                            @else 
                                <option value="{{ $habilitacao['codhab'] }}">{{ $habilitacao['codhab'] }} - {{ $habilitacao['nomhab'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div> 
                <div class="form-group">
                    <label for="numcredisoptelt">Nº de créditos exigidos em displinas optativas eletivas</label>
                    <input class="form-control" id="numcredisoptelt" name="numcredisoptelt" pattern="\d*" 
                        value="{{ $curriculo['numcredisoptelt'] }}" placeholder="Nº de créditos exigidos em displinas optativas eletivas" 
                        type="text" required>
                </div>
                <div class="form-group">
                    <label for="numcredisoptliv">Nº de créditos exigidos em displinas optativas livres</label>
                    <input class="form-control" id="numcredisoptliv" name="numcredisoptliv"  pattern="\d*" 
                        value="{{ $curriculo['numcredisoptliv'] }}" placeholder="Nº de créditos exigidos em displinas optativas livres" 
                        type="text" required>
                </div>
                <div class="form-group">
                    <label for="dtainicrl">Ano de ingresso</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input id="dtainicrl" name="dtainicrl" class="form-control datepicker" 
                            value="{{ Carbon\Carbon::parse($curriculo['dtainicrl'])->format('d/m/Y') }}"
                            data-inputmask="'alias': 'dd/mm/yyyy'" data-mask="" type="text" required>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
          </form> 
    </div>

@stop

@section('js')

    <script type="text/javascript">
    $(function () {
        //Initialize Select2 Elements
        $(".select2").select2({
            placeholder:    "Selecione",
            allowClear:     true
        });
        
        //Datepicker
        $(".datepicker").datepicker({
            format:         "dd/mm/yyyy",
            viewMode:       "years", 
            minViewMode:    "years",
            autoclose:      true
        });
    })
    </script>

@stop
