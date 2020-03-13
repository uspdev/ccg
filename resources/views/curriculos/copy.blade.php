@extends('adminlte::page')

@section('title', config('app.name') . ' - Currículos - Copiar Currículo ' . $curriculo['id'])

@section('content_header')
    <h1>Copiar Currículo {{ $curriculo['id'] }}</h1>
@stop

@section('content')

    @include('flash')

    <div class="box box-primary">
        <form id="copyCurriculo" role="form" method="POST" action="/curriculos/{{ $curriculo['id'] }}/copy">
            {{ csrf_field() }}
            <div class="box-body">
                <div class="form-group">
                    <label>Curso</label>
                    <select class="form-control select2" style="width: 100%;" id="codcur" name="codcur" required disabled>                   
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
                    <select class="form-control select2" style="width: 100%;" id="codhab" name="codhab" required disabled>
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
                            value=""
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
                <button type="submit" class="btn btn-primary btn-sm" 
                    onclick="document.getElementById('codcur').disabled = false; document.getElementById('codhab').disabled = false;">Salvar</button>                 
            </div>   
        </form> 
        <div class="box-body table-responsive">                     
            <table class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
                <thead>
                    <tr>
                        <th><label>Diciplinas Obrigatórias</label></th>
                    </tr>                     
                    <tr>
                        <th>Disciplinas</th>
                    </tr>                                          
                </thead>
                <tbody>                                                     
                    @foreach ($disciplinasObrigatorias as $disciplinasObrigatoria)                   
                        <tr>
                            <td style="width: 70%;">{{ $disciplinasObrigatoria['coddis'] }} - 
                                {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinasObrigatoria['coddis']) }}
                                @if (App\DisciplinasObrigatoriasEquivalente::where('id_dis_obr', $disciplinasObrigatoria->id)->get()->count() > 0)
                                    @foreach (App\DisciplinasObrigatoriasEquivalente::where('id_dis_obr', $disciplinasObrigatoria->id)->orderBy('coddis', 'asc')->get() as $disciplinaObrigatoriaEquivalente)
                                        <br />
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <strong>{{ $disciplinaObrigatoriaEquivalente->tipeqv }}</strong> 
                                        {{ $disciplinaObrigatoriaEquivalente->coddis }}
                                        {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinaObrigatoriaEquivalente->coddis) }}
                                    @endforeach
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
                    </tr>                     
                    <tr>
                        <th>Disciplinas</th>
                    </tr>                                           
                </thead>
                <tbody>                                                      
                    @foreach ($disciplinasOptativasEletivas as $disciplinasOptativasEletiva)                   
                        <tr>
                            <td style="width: 70%;">{{ $disciplinasOptativasEletiva['coddis'] }} - 
                                {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinasOptativasEletiva['coddis']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>  
            <br />             
            <table class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
                <thead>
                    <tr>
                        <th><label>Diciplinas Licenciaturas (Faculdade de Educação)</label></th>
                    </tr>                     
                    <tr>
                        <th>Disciplinas</th>
                    </tr>                                           
                </thead>
                <tbody>                                                   
                    @foreach ($disciplinasLicenciaturas as $disciplinasLicenciatura)                   
                        <tr>
                            <td style="width: 70%;">{{ $disciplinasLicenciatura['coddis'] }} - 
                                {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinasLicenciatura['coddis']) }}
                                @if (App\DisciplinasLicenciaturasEquivalente::where('id_dis_lic', $disciplinasLicenciatura->id)->get()->count() > 0)
                                    @foreach (App\DisciplinasLicenciaturasEquivalente::where('id_dis_lic', $disciplinasLicenciatura->id)->orderBy('coddis', 'asc')->get() as $disciplinaLicenciaturaEquivalente)
                                        <br />
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <strong>{{ $disciplinaLicenciaturaEquivalente->tipeqv }}</strong> 
                                        {{ $disciplinaLicenciaturaEquivalente->coddis }}
                                        {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinaLicenciaturaEquivalente->coddis) }}
                                    @endforeach
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