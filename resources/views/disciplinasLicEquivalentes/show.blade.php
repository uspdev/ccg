@extends('adminlte::page')

@section('title', env('APP_NAME') . ' - Currículo ' . $curriculo['id'] . 
    ' - Disciplinas Licenciaturas Equivalentes - ' . $disciplinasLicenciatura['coddis'])

@section('content_header')
<h1>Curriculo {{ $curriculo['id'] }} - Disciplinas Licenciaturas Equivalentes - {{ $disciplinasLicenciatura['coddis'] }}</h1>
@stop

@section('content')

    @include('flash')

    <div class="box box-primary">
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
                            <label>Disciplina Licenciatura - 
                            {{ $disciplinasLicenciatura['coddis'] }} - 
                            {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinasLicenciatura['coddis']) }}</label>                               
                        </th>
                        <th>&nbsp;</th>
                    </tr>         
                    <tr>
                        <th><label>Diciplinas Licenciaturas Equivalentes</label></th>
                        <th>&nbsp;</th>
                    </tr>                                                            
                    <tr>
                        <th>Disciplinas</th>
                        <th>Ações</th>
                    </tr>                                                     
                </thead>                            
                <tbody>                               
                    @foreach($disciplinasLicenciaturasEquivalentes as $disciplinasLicenciaturasEquivalente)
                        <tr>
                            <td>{{ $disciplinasLicenciaturasEquivalente['coddis'] }} - 
                                {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinasLicenciaturasEquivalente['coddis']) }}</td>
                            <td>    
                                <form role="form" method="POST" action="/disciplinasLicEquivalentes/{{ $disciplinasLicenciaturasEquivalente->id }}">
                                    {{ csrf_field() }}
                                    {{ method_field('delete') }}                                  
                                    <button type="submit" class="btn btn-danger btn-xs" title="Apagar disciplina">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </button> 
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>                          
            </table>               
        </div>
        <div class="box-footer">
            <button type="button" class="btn btn-info btn-sm" 
                onclick='location.href="/curriculos/{{ $curriculo->id }}";' title="Ver Currículo">
                <span class="glyphicon glyphicon-eye-open"></span>&nbsp;&nbsp;&nbsp;Ver Currículo
            </button>                 
        </div>   
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
                pageLength  : 25
            });
        })
    </script>

@stop