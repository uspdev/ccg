@extends('adminlte::page')

@section('title', env('APP_NAME') . ' - Currículo ' . $curriculo['id'])

@section('content_header')
<h1>Currículo {{ $curriculo['id'] }}</h1>
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
            <div class="box-body table-responsive">
                <button type="button" class="btn btn-success btn-sm" title="Adicionar Disciplina Obrigatória" 
                    onclick="location.href='/disciplinasObrigatorias/create/{{ $curriculo['id'] }}';">
                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;&nbsp;Adicionar Disciplina Obrigatória
                </button>  
                <button type="button" class="btn btn-success btn-sm" title="Adicionar Disciplina Obrigatória" onclick="location.href='';">
                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;&nbsp;Adicionar Disciplina Optativa Eletiva
                </button>  
                <button type="button" class="btn btn-success btn-sm" title="Adicionar Disciplina Obrigatória" onclick="location.href='';">
                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;&nbsp;Adicionar Disciplina Licenciaturas (Faculdade de Educação)
                </button>
            </div>            
            <table class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th colspan="2"></th>
                    </tr>                                          
                </thead>
                <tbody>                                                   
                    <tr>
                        <td colspan="2"><label>Diciplinas Obrigatórias</label></td>
                    </tr>   
                    @foreach ($disciplinasObrigatorias as $disciplinasObrigatoria)                   
                        <tr>
                            <td>{{ $disciplinasObrigatoria['coddis'] }} - 
                                {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinasObrigatoria['coddis']) }}</td>
                            <td>
                                <form role="form" method="POST" action="/disciplinasObrigatorias/{{ $disciplinasObrigatoria->id }}">
                                {{ csrf_field() }}
                                {{ method_field('delete') }}      
                                <button type="button" class="btn btn-info btn-xs" title="Disciplinas Obrigatórias Equivalentes">
                                    <span class="glyphicon glyphicon-list-alt"></span>
                                </button>
                                <button type="submit" class="btn btn-danger btn-xs" title="Apagar disciplina">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>     
                                </form>                                                                    
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <table class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th colspan="2"></th>
                    </tr>                                          
                </thead>
                <tbody>                                                   
                    <tr>
                        <td colspan="2"><label>Diciplinas Optativas Eletivas</label></td>
                    </tr>   
                    @foreach ($disciplinasObrigatorias as $disciplinasObrigatoria)                   
                        <tr>
                            <td>{{ $disciplinasObrigatoria['coddis'] }} - 
                                {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinasObrigatoria['coddis']) }}</td>
                            <td>
                                <form role="form" method="POST" action="/disciplinasObrigatorias/{{ $disciplinasObrigatoria->id }}">
                                {{ csrf_field() }}
                                {{ method_field('delete') }}      
                                <button type="button" class="btn btn-info btn-xs" title="Disciplinas Obrigatórias Equivalentes">
                                    <span class="glyphicon glyphicon-list-alt"></span>
                                </button>
                                <button type="submit" class="btn btn-danger btn-xs" title="Apagar disciplina">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>     
                                </form>                                                                    
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>   
            <table class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th colspan="2"></th>
                    </tr>                                          
                </thead>
                <tbody>                                                   
                    <tr>
                        <td colspan="2"><label>Diciplinas Licenciaturas (Faculdade de Educação)</label></td>
                    </tr>   
                    @foreach ($disciplinasObrigatorias as $disciplinasObrigatoria)                   
                        <tr>
                            <td>{{ $disciplinasObrigatoria['coddis'] }} - 
                                {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinasObrigatoria['coddis']) }}</td>
                            <td>
                                <form role="form" method="POST" action="/disciplinasObrigatorias/{{ $disciplinasObrigatoria->id }}">
                                {{ csrf_field() }}
                                {{ method_field('delete') }}      
                                <button type="button" class="btn btn-info btn-xs" title="Disciplinas Obrigatórias Equivalentes">
                                    <span class="glyphicon glyphicon-list-alt"></span>
                                </button>
                                <button type="submit" class="btn btn-danger btn-xs" title="Apagar disciplina">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>     
                                </form>                                                                    
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>                     
            <div class="box-body table-responsive">
                <button type="button" class="btn btn-primary btn-sm" title="Editar" 
                    onclick="location.href='/curriculos/{{ $curriculo['id'] }}/edit';">
                    <span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;&nbsp;Editar Currículo
                </button>              
            </div>
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
                pageLength  : 10
            });

        });

    </script>

@stop