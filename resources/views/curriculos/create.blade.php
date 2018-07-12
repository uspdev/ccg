@extends('adminlte::page')

@section('title', env('APP_NAME') . ' - Currículos - Adicionar Currículo')

@section('content_header')
    <h1>Adicionar Currículo</h1>
@stop

@section('content')

    @include('flash')

    <div class="box box-primary">
        <form role="form">
            <div class="box-body">
                <div class="form-group">
                    <label>Curso</label>
                    <select class="form-control select2" style="width: 100%;" id="codcur" required>
                        <option></option>                      
                        @foreach ($cursos as $curso)
                            <option value="{{ $curso['codcur'] }}">{{ $curso['codcur'] }} - {{ $curso['nomcur'] }}</option>
                        @endforeach
                    </select>
                </div>              
                <div class="form-group">
                    <label>Habilitação</label>
                    <select class="form-control select2" style="width: 100%;" id="codhab" required>
                        <option></option>
                        @foreach ($habilitacoes as $habilitacao)
                            <option value="{{ $habilitacao['codhab'] }}">{{ $habilitacao['codhab'] }} - {{ $habilitacao['nomhab'] }}</option>
                        @endforeach
                    </select>
                </div> 
                <div class="form-group">
                    <label for="numcrediselt">Nº de créditos exigidos em displinas optativas eletivas</label>
                    <input class="form-control" id="numcrediselt" placeholder="Nº de créditos exigidos em displinas optativas eletivas" type="text" required>
                </div>
                <div class="form-group">
                    <label for="numcredisoptliv">Nº de créditos exigidos em displinas optativas livres</label>
                    <input class="form-control" id="numcredisoptliv" placeholder="Nº de créditos exigidos em displinas optativas livres" type="text" required>
                </div>
                <div class="form-group">
                    <label for="dtainicrl">Ano de ingresso</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input class="form-control datepicker" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask="" type="text" required>
                    </div>
                </div>

{{-- As disciplinas só podem ser incluídas após salvar o Currículo                 --}}
{{--                 <div style="border: 1px solid #ccc; padding: 5px;" id="disciplinas">
                    <div class="box-body table-responsive no-padding">
                        <label>Disciplinas obrigatórias</label>
                        <table class="table table-hover table-striped">
                            <tbody>
                                <tr>
                                    <th colspan="2">
                                        <button type="button" class="btn btn-success btn-sm" title="Adicionar Disciplina Obrigatória" onclick="location.href='';">
                                            <span class="glyphicon glyphicon-plus"></span> Adicionar Disciplina Obrigatória
                                        </button>                             
                                    </th>
                                </tr>                                 
                                <tr>
                                    <td>XXX9999 - John Doe</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-xs" title="Disciplinas Obrigatórias Equivalentes">
                                            <span class="glyphicon glyphicon-list-alt"></span>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-xs" title="Apagar disciplina">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </button>                                        
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
            
                    <div class="box-body table-responsive no-padding">
                        <label>Disciplinas Optativas Eletivas</label>
                        <table class="table table-hover table-striped">
                            <tbody>
                                <tr>
                                    <th colspan="2">
                                        <button type="button" class="btn btn-success btn-sm" title="Adicionar Disciplina Obrigatória" onclick="location.href='';">
                                            <span class="glyphicon glyphicon-plus"></span> Adicionar Disciplina Optativa Eletiva
                                        </button>                             
                                    </th>
                                </tr>                                  
                                <tr>
                                    <td>XXX9999 - John Doe</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-xs" title="Disciplinas Obrigatórias Equivalentes">
                                            <span class="glyphicon glyphicon-list-alt"></span>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-xs" title="Apagar disciplina">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </button>                                        
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>  
            
                    <div class="box-body table-responsive no-padding">
                        <label>Disciplinas Licenciaturas (Faculdade de Educação)</label>
                        <table class="table table-hover table-striped">
                            <tbody>
                                <tr>
                                    <th colspan="2">
                                        <button type="button" class="btn btn-success btn-sm" title="Adicionar Disciplina Obrigatória" onclick="location.href='';">
                                            <span class="glyphicon glyphicon-plus"></span> Adicionar Disciplina Licenciaturas (Faculdade de Educação)
                                        </button>                             
                                    </th>
                                </tr>                                  
                                <tr>
                                    <td>XXX9999 - John Doe</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-xs" title="Disciplinas Obrigatórias Equivalentes">
                                            <span class="glyphicon glyphicon-list-alt"></span>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-xs" title="Apagar disciplina">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </button>                                        
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div> 
                </div> --}}

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
