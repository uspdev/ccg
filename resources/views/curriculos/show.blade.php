@extends('adminlte::page')

@section('title', config('app.name') . ' - Currículo ' . $curriculo['id'])

@section('content_header')
<h1>Currículo {{ $curriculo['id'] }}</h1>
@stop

@section('content')

    @include('flash')
    
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab_1" data-toggle="tab">Currículo</a>
            </li>
            <li>
                <a href="#tab_2" data-toggle="tab" onclick="location.href='/curriculos/{{ $curriculo['id'] }}/alunos';">Alunos</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <div class="box-primary">
                    <div class="box-body table-responsive no-padding">
                        <table class="table">
                            <tr>
                                <th style="width: 30%;">Curso</th>
                                <td style="width: 70%;">{{ $curriculo['codcur'] }} - {{ Uspdev\Replicado\Graduacao::nomeCurso($curriculo['codcur']) }}</td>
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
                            <button type="button" class="btn btn-primary btn-sm" title="Editar" 
                                onclick="location.href='/curriculos/{{ $curriculo['id'] }}/edit';">
                                <span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;&nbsp;Editar Currículo
                            </button>              
                            <button type="button" class="btn btn-success btn-sm" title="Adicionar Disciplina Obrigatória" 
                                onclick="location.href='/disciplinasObrigatorias/create/{{ $curriculo['id'] }}';">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;&nbsp;Adicionar Disciplina Obrigatória
                            </button>  
                            <button type="button" class="btn btn-success btn-sm" title="Adicionar Disciplina Optativa Eletiva" 
                                onclick="location.href='/disciplinasOptativasEletivas/create/{{ $curriculo['id'] }}';">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;&nbsp;Adicionar Disciplina Optativa Eletiva
                            </button>  
                            <button type="button" class="btn btn-success btn-sm" title="Adicionar Disciplina Licenciaturas (Faculdade de Educação)" 
                                onclick="location.href='/disciplinasLicenciaturas/create/{{ $curriculo['id'] }}';">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;&nbsp;Adicionar Disciplina Licenciaturas (Faculdade de Educação)
                            </button>
                        </div>              
                        <table class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th><label>Diciplinas Obrigatórias</label></th>
                                    <th>&nbsp;</th>
                                </tr>                     
                                <tr>
                                    <th>Disciplinas</th>
                                    <th>Ações</th>
                                </tr>                                          
                            </thead>
                            <tbody>                                                     
                                @foreach ($disciplinasObrigatorias as $disciplinasObrigatoria)                   
                                    <tr>
                                        <td style="width: 70%;">{{ $disciplinasObrigatoria['coddis'] }} - 
                                            {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinasObrigatoria['coddis']) }}</td>
                                        <td style="width: 30%;">   
                                            {{-- Se existe disciplina equivalente cadastrada, mostra as equivalentes --}}
                                            @if (App\DisciplinasObrigatoriasEquivalente::where('id_dis_obr', $disciplinasObrigatoria->id)->get()->count() > 0)
                                                <button type="button" class="btn btn-info btn-xs" title="Disciplinas Obrigatórias Equivalentes" 
                                                    onclick="location.href='/disciplinasObrEquivalentes/{{ $disciplinasObrigatoria->id }}';">
                                                    <span class="glyphicon glyphicon-eye-open"></span>
                                                </button>
                                            @endif
                                            <button style="float: left; margin-right: 3px; margin-top: 1px;" type="button" class="btn btn-success btn-xs" 
                                                title="Adicionar Disciplinas Obrigatórias Equivalentes" 
                                                onclick="location.href='/disciplinasObrEquivalentes/create/{{ $disciplinasObrigatoria->id }}';">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                            {{-- Se não existe disciplina equivalente cadastrada, pode apagar --}}
                                            @if (App\DisciplinasObrigatoriasEquivalente::where('id_dis_obr', $disciplinasObrigatoria->id)->get()->count() == 0) 
                                                <form style="float: left; margin-right: 3px;" role="form" method="POST" action="/disciplinasObrigatorias/{{ $disciplinasObrigatoria->id }}">
                                                    {{ csrf_field() }}
                                                    {{ method_field('delete') }}                                  
                                                    <button type="submit" class="btn btn-danger btn-xs" title="Apagar disciplina">
                                                        <span class="glyphicon glyphicon-trash"></span>
                                                    </button> 
                                                </form>
                                            {{-- Se não, exibe modal avisando --}} 
                                            @else                                                                         
                                                <button type="button" class="btn btn-danger btn-xs" title="Apagar disciplina" 
                                                    data-toggle="modal" data-target="#diciplinas{{ $disciplinasObrigatoria->id }}">
                                                    <span class="glyphicon glyphicon-trash"></span>
                                                </button> 
                                                {{-- Modais com as disciplinas equivalentes --}}                                
                                                <div class="modal modal-danger fade" id="diciplinas{{ $disciplinasObrigatoria->id }}">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title">Esta Disciplina possui Equivalentes cadastradas!</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Disciplina Obrigatória: <strong>
                                                                    {{ $disciplinasObrigatoria->coddis }} - 
                                                                    {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinasObrigatoria->coddis) }}
                                                                </strong></p>
                                                                <p><strong>As Diciplinas abaixo serão automaticamente removidas junto com a Disciplina Obrigatória</strong></p>
                                                                <p><strong>Equivalentes</strong>                                                        
                                                                @foreach (App\DisciplinasObrigatoriasEquivalente::where('id_dis_obr', $disciplinasObrigatoria->id)->get() as $obrigatoriaEquivalente)
                                                                    <br />{{ $obrigatoriaEquivalente['coddis'] }} - 
                                                                        {{ Uspdev\Replicado\Graduacao::nomeDisciplina($obrigatoriaEquivalente['coddis']) }}
                                                                @endforeach
                                                                </p>                                                                                                  
                                                            </div>
                                                            <form role="form" method="POST" action="/disciplinasObrigatorias/{{ $disciplinasObrigatoria->id }}">
                                                                {{ csrf_field() }}
                                                                {{ method_field('delete') }} 
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
                                                                <button type="submit" class="btn btn-outline">Apagar</button>
                                                            </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>                                
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br />
                        <table class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th><label>Diciplinas Optativas Eletivas</label></th>
                                    <th>&nbsp;</th>
                                </tr>                     
                                <tr>
                                    <th>Disciplinas</th>
                                    <th>Ações</th>
                                </tr>                                           
                            </thead>
                            <tbody>                                                      
                                @foreach ($disciplinasOptativasEletivas as $disciplinasOptativasEletiva)                   
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
                        <br />             
                        <table class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th><label>Diciplinas Licenciaturas (Faculdade de Educação)</label></th>
                                    <th>&nbsp;</th>
                                </tr>                     
                                <tr>
                                    <th>Disciplinas</th>
                                    <th>Ações</th>
                                </tr>                                           
                            </thead>
                            <tbody>                                                   
                                @foreach ($disciplinasLicenciaturas as $disciplinasLicenciatura)                   
                                    <tr>
                                        <td style="width: 70%;">{{ $disciplinasLicenciatura['coddis'] }} - 
                                            {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinasLicenciatura['coddis']) }}</td>
                                        <td style="width: 30%;">   
                                            {{-- Se existe disciplina equivalente cadastrada, mostra as equivalentes --}}
                                            @if (App\DisciplinasLicenciaturasEquivalente::where('id_dis_lic', $disciplinasLicenciatura->id)->get()->count() > 0)
                                                <button type="button" class="btn btn-info btn-xs" title="Disciplinas Licenciaturas Equivalentes" 
                                                    onclick="location.href='/disciplinasLicEquivalentes/{{ $disciplinasLicenciatura->id }}';">
                                                    <span class="glyphicon glyphicon-eye-open"></span>
                                                </button>
                                            @endif
                                            <button style="float: left; margin-right: 3px; margin-top: 1px;" type="button" class="btn btn-success btn-xs" 
                                                title="Adicionar Disciplinas Licenciaturas Equivalentes" 
                                                onclick="location.href='/disciplinasLicEquivalentes/create/{{ $disciplinasLicenciatura->id }}';">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                            {{-- Se não existe disciplina equivalente cadastrada, pode apagar --}}
                                            @if (App\DisciplinasLicenciaturasEquivalente::where('id_dis_lic', $disciplinasLicenciatura->id)->get()->count() == 0) 
                                                <form style="float: left; margin-right: 3px;" role="form" method="POST" action="/disciplinasLicenciaturas/{{ $disciplinasLicenciatura->id }}">
                                                    {{ csrf_field() }}
                                                    {{ method_field('delete') }}                                  
                                                    <button type="submit" class="btn btn-danger btn-xs" title="Apagar disciplina">
                                                        <span class="glyphicon glyphicon-trash"></span>
                                                    </button> 
                                                </form>
                                            {{-- Se não, exibe modal avisando --}} 
                                            @else                                                                         
                                                <button type="button" class="btn btn-danger btn-xs" title="Apagar disciplina" 
                                                    data-toggle="modal" data-target="#diciplinas{{ $disciplinasLicenciatura->id }}">
                                                    <span class="glyphicon glyphicon-trash"></span>
                                                </button> 
                                                {{-- Modais com as disciplinas equivalentes --}}                                
                                                <div class="modal modal-danger fade" id="diciplinas{{ $disciplinasLicenciatura->id }}">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title">Esta Disciplina possui Equivalentes cadastradas!</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Disciplina Licenciatura: <strong>
                                                                    {{ $disciplinasLicenciatura->coddis }} - 
                                                                    {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinasLicenciatura->coddis) }}
                                                                </strong></p>
                                                                <p><strong>As Diciplinas abaixo serão automaticamente removidas junto com a Disciplina Licenciatura</strong></p>
                                                                <p><strong>Equivalentes</strong>                                                        
                                                                @foreach (App\DisciplinasLicenciaturasEquivalente::where('id_dis_lic', $disciplinasLicenciatura->id)->get() as $licenciaturaEquivalente)
                                                                    <br />{{ $licenciaturaEquivalente['coddis'] }} - 
                                                                        {{ Uspdev\Replicado\Graduacao::nomeDisciplina($licenciaturaEquivalente['coddis']) }}
                                                                @endforeach
                                                                </p>                                                                                                  
                                                            </div>
                                                            <form role="form" method="POST" action="/disciplinasLicenciaturas/{{ $disciplinasLicenciatura->id }}">
                                                                {{ csrf_field() }}
                                                                {{ method_field('delete') }} 
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
                                                                <button type="submit" class="btn btn-outline">Apagar</button>
                                                            </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>                                
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>                                   
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab_2">
          		<div class="box-primary">
                    <table class="table">
                        <tr>
                            <th style="width: 30%;">Curso</th>
                            <td style="width: 70%;">{{ $curriculo['codcur'] }} - {{ Uspdev\Replicado\Graduacao::nomeCurso($curriculo['codcur']) }}</td>
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
                    <br />
                    <table class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
                        <thead>                  
                            <tr>
                                <th style="width: 70%;">Nome</th>
                                <th style="width: 30%;">Ações</th>
                            </tr>                                          
                        </thead>
                        <tbody>                         
                        @isset ($alunosCurriculo)
                            @foreach ($alunosCurriculo as $aluno)
                            <tr>
                                <td>{{ $aluno['nompes'] }}</td>
                                <td>
                                    <button type="button" class="btn btn-success btn-xs" title="Analisar Currículo">
                                        <span class="glyphicon glyphicon-ok"></span>
                                    </button>                                
                                </td>
                            </tr> 
                            @endforeach
                        @endisset
                        </tbody>
                    </table>
          		</div>
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
                pageLength  : 100
            });

        });

        @if (substr(request()->path(), -6, 6) == 'alunos')
            /* Active tab-pane */ 
            $(document).ready(function(){
                activaTab('tab_2');
            });
        @else
            $(document).ready(function(){
                activaTab('tab_1');
            });
        @endif

        function activaTab(tab){
            $('.nav-tabs a[href="#' + tab + '"]').tab('show');
        };

    </script>

@stop