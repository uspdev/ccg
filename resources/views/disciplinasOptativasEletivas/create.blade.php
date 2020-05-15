@extends('adminlte::page')

@section('title', config('app.name') . ' - Currículo ' . $curriculo['id'] . ' - Disciplinas Optativas Eletivas')

@section('content_header')
    <h1>Curriculo {{ $curriculo['id'] }} - Adicionar Disciplinas Optativas Eletivas</h1>
@stop

@section('content')

    @include('flash')

    <div class="box box-primary">
        <form role="form" method="POST" action="/disciplinasOptativasEletivas/create/{{ $curriculo['id'] }}">
                        
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
                        <th>Nº de créditos (aula) exigidos em displinas optativas eletivas</td>
                        <td>{{ $curriculo['numcredisoptelt'] }}</td>
                    </tr>
                    <tr>
                        <th>Nº de créditos (aula) exigidos em displinas optativas livres</td>
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
                                <td>{{ $disciplinasOptativasEletiva['coddis'] }} - 
                                    {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinasOptativasEletiva['coddis']) }}</td>
                                <td>
                                    &nbsp;
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2">
                                <div class="form-group">
                                    <label>Adicionar Disciplina Optativa Eletiva</label>
                                    <select class="form-control select2" style="width: 100%;" id="coddiselt" name="coddiselt">
                                        <option></option>                      
                                            @foreach ($disciplinas as $disciplina)
                                                <option value="{{ $disciplina['coddis'] }}">{{ $disciplina['coddis'] }} - {{ $disciplina['nomdis'] }}</option>
                                            @endforeach
                                    </select>
                                </div> 
                                <div class="form-group">
                                    <label>Adicionar Disciplinas</label>
                                    <textarea id="lstcoddis" name="lstcoddis" class="form-control" rows="3" 
                                        placeholder="Digite ou cole aqui os códigos das disciplinas separados por vírgula"></textarea>
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
        })
    </script>

@stop