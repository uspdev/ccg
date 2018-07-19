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
                            @if ($curso['codcur'] == $curriculo['codcur'])
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
                        @foreach ($cursosHabilitacoes as $habilitacao)
                            @if ($habilitacao['codhab'] == $curriculo['codhab'] and $habilitacao['codcur'] == $curriculo['codcur'])
                                <option value="{{ $habilitacao['codhab'] }}" selected>{{ $habilitacao['codcur'] }} - {{ $habilitacao['codhab'] }} - {{ $habilitacao['nomhab'] }}</option>
                            @else 
                                <option value="{{ $habilitacao['codhab'] }}">{{ $habilitacao['codcur'] }} - {{ $habilitacao['codhab'] }} - {{ $habilitacao['nomhab'] }}</option>
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
                <button type="submit" class="btn btn-primary btn-sm">Salvar</button>
                <button type="button" class="btn btn-success btn-sm" title="Adicionar Disciplina Obrigatória" 
                    onclick="location.href='/disciplinasObrigatorias/create/{{ $curriculo['id'] }}';">
                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;&nbsp;Adicionar Disciplina Obrigatória
                </button>  
                <button type="button" class="btn btn-success btn-sm" title="Adicionar Disciplina Optativa Eletiva" 
                    onclick="location.href='/disciplinasOptativasEletivas/create/{{ $curriculo['id'] }}';">
                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;&nbsp;Adicionar Disciplina Optativa Eletiva
                </button>  
                <button type="button" class="btn btn-success btn-sm" title="Adicionar Adicionar Disciplina Licenciaturas (Faculdade de Educação)" 
                    onclick="location.href='/disciplinasLicenciaturas/create/{{ $curriculo['id'] }}';">
                    <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;&nbsp;Adicionar Disciplina Licenciaturas (Faculdade de Educação)
                </button>                 
            </div>   
        </form> 
        <div class="box-body table-responsive no-padding">                     
            <table class="table table-hover table-striped datatable">
                <thead>
                    <tr>
                        <th colspan="2"></th>
                    </tr>                                          
                </thead>                        
                <tbody>                                
                    <tr>
                        <td colspan="2"><label>Diciplinas Obrigatórias</label></td>
                    </tr>                             
                    @foreach($disciplinasObrigatorias as $disciplinasObrigatoria)
                        <tr>
                            <td style="width: 70%;">{{ $disciplinasObrigatoria['coddis'] }} - 
                                {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinasObrigatoria['coddis']) }}</td>
                            <td style="width: 30%;">
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
            <table class="table table-hover table-striped datatable">
                <thead>
                    <tr>
                        <th colspan="2"></th>
                    </tr>                                          
                </thead>                        
                <tbody>                                
                    <tr>
                        <td colspan="2"><label>Diciplinas Optativas Eletivas</label></td>
                    </tr>                             
                    @foreach($disciplinasOptativasEletivas as $disciplinasOptativasEletiva)
                        <tr>
                            <td style="width: 70%;">{{ $disciplinasOptativasEletiva['coddis'] }} - 
                                {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinasOptativasEletiva['coddis']) }}</td>
                            <td style="width: 30%;">
                                <form role="form" method="POST" action="/disciplinasOptativasEletivas/{{ $disciplinasOptativasEletiva->id }}">
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
            <table class="table table-hover table-striped datatable">
                <thead>
                    <tr>
                        <th colspan="2"></th>
                    </tr>                                          
                </thead>                        
                <tbody>                                
                    <tr>
                        <td colspan="2"><label>Diciplinas Licenciaturas (Faculdade de Educação)</label></td>
                    </tr>                             
                    @foreach($disciplinasLicenciaturas as $disciplinasLicenciatura)
                        <tr>
                            <td style="width: 70%;">{{ $disciplinasLicenciatura['coddis'] }} - 
                                {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinasLicenciatura['coddis']) }}</td>
                            <td style="width: 30%;">
                                <form role="form" method="POST" action="/disciplinasLicenciaturas/{{ $disciplinasLicenciatura->id }}">
                                {{ csrf_field() }}
                                {{ method_field('delete') }}                                   
                                <button type="button" class="btn btn-info btn-xs" title="Disciplinas Licenciaturas Equivalentes">
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