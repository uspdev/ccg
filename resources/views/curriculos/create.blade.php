@extends('adminlte::page')

@section('title', config('app.name') . ' - Currículos - Adicionar Currículo')

@section('content_header')
    <h1>Adicionar Currículo</h1>
@stop

@section('content')

    @include('flash')

    <div class="box box-primary">
        <form role="form" method="POST" action="/curriculos">

            {{ csrf_field() }}

            <div class="box-body">
                <div class="form-group">
                    <label>Curso</label>
                    <select class="form-control select2" style="width: 100%;" id="codcur" name="codcur" required>
                        <option></option>                      
                        @foreach ($cursos as $curso)
                            <option value="{{ $curso['codcur'] }}">{{ $curso['codcur'] }} - {{ $curso['nomcur'] }}</option>
                        @endforeach
                    </select>
                </div>              
                <div class="form-group">
                    <label>Habilitação</label>
                    <select class="form-control select2" style="width: 100%;" id="codhab" name="codhab" required>
                        <option></option>
                        @foreach ($cursosHabilitacoes as $habilitacao)
                            <option value="{{ $habilitacao['codhab'] }}">{{ $habilitacao['codcur'] }} - {{ $habilitacao['codhab'] }} - {{ $habilitacao['nomhab'] }}</option>
                        @endforeach
                    </select>
                </div> 
                <div class="form-group">
                    <label for="numcredisoptelt">Nº de créditos exigidos em displinas optativas eletivas</label>
                    <input class="form-control" id="numcredisoptelt" name="numcredisoptelt" pattern="\d*"  
                        placeholder="Nº de créditos exigidos em displinas optativas eletivas" type="text" required>
                </div>
                <div class="form-group">
                    <label for="numcredisoptliv">Nº de créditos exigidos em displinas optativas livres</label>
                    <input class="form-control" id="numcredisoptliv" name="numcredisoptliv"  pattern="\d*" 
                        placeholder="Nº de créditos exigidos em displinas optativas livres" type="text" required>
                </div>
                <div class="form-group">
                    <label for="dtainicrl">Ano de ingresso</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input id="dtainicrl" name="dtainicrl" class="form-control datepicker" 
                            data-inputmask="'alias': 'dd/mm/yyyy'" data-mask="" type="text" required>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Salvar</button>
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