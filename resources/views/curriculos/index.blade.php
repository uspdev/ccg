@extends('adminlte::page')

@section('title', env('APP_NAME') . ' - Currículos')

@section('content_header')
    <h1>Currículos</h1>
@stop

@section('content')

    <style>
        #curriculos tr:hover {
            background-color: #f39c12;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
        }
    </style>

    @include('flash')

    <div class="box box-primary">
        <div class="box-body">
            <table id="curriculos" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th colspan="4">
                            <button type="button" class="btn btn-success" title="Adicionar Currículo">
                                <span class="glyphicon glyphicon-plus"></span> Adicionar Currículo
                            </button>                             
                        </th>
                    </tr>                    
                    <tr>
                        <th>Curso</th>
                        <th>Habilitação</th>
                        <th>Ingresso</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                
                @foreach ($curriculos as $curriculo)

                    <tr>
                        <td>{{ $curriculo->codcur }} - {{ Uspdev\Replicado\Graduacao::nomeCurso($curriculo->codcur) }}</td>
                        <td>{{ $curriculo->codhab }} - {{ Uspdev\Replicado\Graduacao::nomeHabilitacao($curriculo->codhab, $curriculo->codcur) }}</td>
                        <td>{{ Carbon\Carbon::parse($curriculo->dtainicrl)->format('Y') }}</td>
                        <td>
                            <button type="button" class="btn btn-info btn-xs" title="Ver Currículo">
                                <span class="glyphicon glyphicon-eye-open"></span>
                            </button> 
                            <button type="button" class="btn btn-primary btn-xs" title="Editar Currículo">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </button>
                            <button type="button" class="btn btn-success btn-xs" title="Analisar Currículo">
                                <span class="glyphicon glyphicon-ok"></span>
                            </button>
                            <button type="button" class="btn btn-danger btn-xs" title="Apagar Currículo">
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>
                        </td>
                    </tr>
                
                @endforeach
                
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">
                            <button type="button" class="btn btn-success" title="Adicionar Currículo">
                                <span class="glyphicon glyphicon-plus"></span> Adicionar Currículo
                            </button>                             
                        </th>
                    </tr>                        
                    <tr>
                        <th>Curso</th>
                        <th>Habilitação</th>
                        <th>Ingresso</th>
                        <th>Ações</th>
                    </tr>
                </tfoot>
            </table>
        </div>  
    </div>

    @if (isset($curriculos))

    @endif

@stop

@section('js')

    <script type="text/javascript">
    $(function () {
        $('#curriculos').DataTable({
            'language'    : {
                'url'     : '//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json'
            },  
            'paging'      : true,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : true
        })
    })
    </script>

@stop
