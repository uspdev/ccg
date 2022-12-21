@extends('layouts.app')

@section('title', config('app.name') . ' - Currículo ' . $curriculo['id'] . 
    ' - Disciplinas Obrigatórias Equivalentes - ' . $disciplinasObrigatoria['coddis'])

@section('content')

    @include('flash')
    <h3>Curriculo {{ $curriculo['id'] }} - Disciplinas Obrigatórias Equivalentes - {{ $disciplinasObrigatoria['coddis'] }}</h3>

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
                            <label>Disciplina Obrigatória - 
                            {{ $disciplinasObrigatoria['coddis'] }} - 
                            {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinasObrigatoria['coddis']) }}</label>    
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <button type="button" class="btn btn-success btn-sm" title="Adicionar Disciplina Obrigatória Equivalente" 
                                onclick="location.href='disciplinasObrEquivalentes/create/{{ $disciplinasObrigatoria->id }}';">
                                <i class="fas fa-plus"></i>&nbsp;&nbsp;&nbsp;Adicionar Disciplina Obrigatória Equivalente
                            </button>                             
                        </th>
                        <th>&nbsp;</th>
                    </tr>         
                    <tr>
                        <th><label>Diciplinas Obrigatórias Equivalentes</label></th>
                        <th>&nbsp;</th>
                    </tr>                                                          
                    <tr>
                        <th>Disciplinas</th>
                        <th>Ações</th>
                    </tr>                                                     
                </thead>                            
                <tbody>                               
                    @foreach($disciplinasObrigatoriasEquivalentes as $disciplinasObrigatoriasEquivalente)
                        <tr>
                            <td>{{ $disciplinasObrigatoriasEquivalente['coddis'] }} - 
                                {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinasObrigatoriasEquivalente['coddis']) }}
                                &nbsp;&nbsp;<strong>{{ $disciplinasObrigatoriasEquivalente['tipeqv'] }}</strong>
                            </td>
                            <td>    
                                <form role="form" method="POST" action="disciplinasObrEquivalentes/{{ $disciplinasObrigatoriasEquivalente->id }}">
                                    {{ csrf_field() }}
                                    {{ method_field('delete') }}                                  
                                    <button type="button" class="btn btn-primary btn-xs" title="Editar" 
                                        onclick="location.href='disciplinasObrEquivalentes/{{ $disciplinasObrigatoriasEquivalente->id }}/edit';">
                                        <i class="far fa-edit"></i>
                                    </button>                                    
                                    <button type="submit" class="btn btn-danger btn-xs confirm" title="Apagar disciplina">
                                        <i class="far fa-trash-alt"></i>
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
                onclick='location.href="curriculos/{{ $curriculo->id }}";' title="Ver Currículo">
                <span class="far fa-eye"></span>&nbsp;&nbsp;&nbsp;Ver Currículo
            </button>                 
        </div>   
    </div>

@stop

@section('javascripts_bottom')
@parent
    
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
            // $('.datatable').DataTable({
            //     language    	: {
            //         url     : '//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json'
            //     },  
            //     paging      	: true,
            //     lengthChange	: true,
            //     searching   	: true,
            //     ordering    	: true,
            //     info        	: true,
            //     autoWidth   	: true,
            //     lengthMenu		: [
			// 		[ 10, 25, 50, 100, -1 ],
			// 		[ '10 linhas', '25 linhas', '50 linhas', '100 linhas', 'Mostar todos' ]
    		// 	],
			// 	pageLength  	: -1
            // });
        })
    </script>

@stop