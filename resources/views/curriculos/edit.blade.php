@extends('adminlte::page')

@section('title', config('app.name') . ' - Currículos - Editar Currículo ' . $curriculo['id'])

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
                    <label for="numtotcredisoptelt">Nº de créditos totais exigidos em displinas optativas eletivas</label>
                    <input class="form-control" id="numtotcredisoptelt" name="numtotcredisoptelt" pattern="\d*" 
                        value="{{ $curriculo['numtotcredisoptelt'] }}" placeholder="Nº de créditos totais exigidos em displinas optativas eletivas" 
                        type="text" required>
                </div>                
                <div class="form-group">
                    <label for="numcredisoptliv">Nº de créditos exigidos em displinas optativas livres</label>
                    <input class="form-control" id="numcredisoptliv" name="numcredisoptliv"  pattern="\d*" 
                        value="{{ $curriculo['numcredisoptliv'] }}" placeholder="Nº de créditos exigidos em displinas optativas livres" 
                        type="text" required>
                </div>
                <div class="form-group">
                    <label for="numtotcredisoptliv">Nº de créditos totais exigidos em displinas optativas livres</label>
                    <input class="form-control" id="numtotcredisoptliv" name="numtotcredisoptliv"  pattern="\d*" 
                        value="{{ $curriculo['numtotcredisoptliv'] }}" placeholder="Nº de créditos totais exigidos em displinas optativas livres" 
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
                <div class="form-group">
                    <label>Observações</label>
                        <textarea id="txtobs" name="txtobs" class="form-control" rows="3" 
                            placeholder="Digite aqui">{{ $curriculo['txtobs'] }}</textarea>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm">Salvar</button>
                <button type="button" class="btn btn-success btn-sm" title="Grade Curricular atual (Jupiter)"
                    onclick="location.href='/curriculos/{{ $curriculo['id'] }}/gradeCurricularJupiter';">
                    <span class="glyphicon glyphicon-plus"></span>&nbsp;Grade Curricular (Jupiter)
                </button>
                <button type="button" class="btn btn-success btn-sm" title="Adicionar Disciplina Obrigatória" 
                    onclick="location.href='/disciplinasObrigatorias/create/{{ $curriculo['id'] }}';">
                    <span class="glyphicon glyphicon-plus"></span>&nbsp;Adicionar Disciplina Obrigatória
                </button>  
                <button type="button" class="btn btn-success btn-sm" title="Adicionar Disciplina Optativa Eletiva" 
                    onclick="location.href='/disciplinasOptativasEletivas/create/{{ $curriculo['id'] }}';">
                    <span class="glyphicon glyphicon-plus"></span>&nbsp;Adicionar Disciplina Optativa Eletiva
                </button>  
                <button type="button" class="btn btn-success btn-sm" title="Adicionar Adicionar Disciplina Licenciaturas (Faculdade de Educação)" 
                    onclick="location.href='/disciplinasLicenciaturas/create/{{ $curriculo['id'] }}';">
                    <span class="glyphicon glyphicon-plus"></span>&nbsp;Adicionar Disciplina Licenciaturas (Faculdade de Educação)
                </button>                 
            </div>   
        </form> 
        <div class="box-body table-responsive">                     
            <table class="table table-hover table-striped datatable">
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
            <table class="table table-hover table-striped datatable">
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
            <br />            
            <table class="table table-hover table-striped datatable">
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
                language    	: {
                    url     : '//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json'
                },  
                paging      	: true,
                lengthChange	: true,
                searching   	: true,
                ordering    	: true,
                info        	: true,
                autoWidth   	: true,
                lengthMenu		: [
					[ 10, 25, 50, 100, -1 ],
					[ '10 linhas', '25 linhas', '50 linhas', '100 linhas', 'Mostar todos' ]
    			],
				pageLength  	: -1
            });

        });

    </script>

@stop