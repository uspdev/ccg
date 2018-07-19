@extends('adminlte::page')

@section('title', env('APP_NAME') . ' - Currículo ' . $curriculo['id'] . ' - Disciplinas Licenciaturas ')

@section('content_header')
    <h1>Curriculo {{ $curriculo['id'] }} - Adicionar Disciplinas Licenciaturas </h1>
@stop

@section('content')

    @include('flash')

    <div class="box box-primary">
        <form role="form" method="POST" action="/disciplinasLicenciaturas/create/{{ $curriculo['id'] }}">
                        
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
                        <th>Nº de créditos exigidos em displinas optativas eletivas</td>
                        <td>{{ $curriculo['numcredisoptelt'] }}</td>
                    </tr>
                    <tr>
                        <th>Nº de créditos exigidos em displinas optativas livres</td>
                        <td>{{ $curriculo['numcredisoptliv'] }}</td>
                    </tr>                                         
                    <tr>
                        <th>Ano de ingresso</td>
                        <td>{{ Carbon\Carbon::parse($curriculo['dtainicrl'])->format('Y') }}</td>
                    </tr>  
                </table>
             
                <table class="table table-bordered table-striped table-hover datatable">
                    <thead>
                        <tr>
                            <th colspan="2"><label>Disciplinas Licenciaturas</label></th>
                        </tr>                                          
                    </thead>                            
                    <tbody>                               
                        @foreach($disciplinasLicenciaturas as $disciplinasLicenciatura)
                            <tr>
                                <td>{{ $disciplinasLicenciatura['coddis'] }} - 
                                    {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinasLicenciatura['coddis']) }}</td>
                                <td>
{{--                                     <form role="form" method="POST" action="/disciplinasLicenciaturas/create/{{ $curriculo->id }}">
                                    {{ csrf_field() }}
                                    {{ method_field('delete') }}     --}}                                
                                    <button type="button" class="btn btn-info btn-xs" title="Disciplinas Licenciaturas Equivalentes">
                                        <span class="glyphicon glyphicon-list-alt"></span>
                                    </button>
{{--                                     <button type="submit" class="btn btn-danger btn-xs" title="Apagar disciplina">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </button> --}}
{{--                                     </form>    --}}                                     
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2">
                                <div class="form-group">
                                    <label>Adicionar Disciplina Licenciatura</label>
                                    <select class="form-control select2" style="width: 100%;" id="coddisobr" name="coddisobr" required>
                                        <option></option>                      
                                            @foreach ($disciplinas as $disciplina)
                                                <option value="{{ $disciplina['coddis'] }}">{{ $disciplina['coddis'] }} - {{ $disciplina['nomdis'] }}</option>
                                            @endforeach
                                    </select>
                                </div>                                 
                            </th>
                        </tr>                                          
                    </tfoot>                            
                </table>               
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm">Salvar</button>
                <button type="button" class="btn btn-info btn-sm" 
                    onclick='location.href="/curriculos/{{ $curriculo->id }}";' title="Ver Currículo">
                    <span class="glyphicon glyphicon-eye-open"></span>&nbsp;&nbsp;&nbsp;Ver Currículo
                </button>
                <button type="button" class="btn btn-primary btn-sm" title="Editar" 
                    onclick="location.href='/curriculos/{{ $curriculo['id'] }}/edit';">
                    <span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;&nbsp;Editar Currículo
                </button>                   
            </div>   
        </form>
    </div>

@stop

@section('js')
    
    <script type="text/javascript">
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2({
                placeholder:    "Selecione",
                allowClear:     true
            });
            
            //Datepicker
            $('.datepicker').datepicker({
                format:         "dd/mm/yyyy",
                viewMode:       "years", 
                minViewMode:    "years",
                autoclose:      true
            });

            // DataTables
            $('.datatable').DataTable({
                language    : {
                    url     : '//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json'
                },  
                paging      : true,
                lengthChange: true,
                searching   : true,
                ordering    : true,
                info        : true,
                autoWidth   : true,
                pageLength  : 10
            });
        })
    </script>

@stop