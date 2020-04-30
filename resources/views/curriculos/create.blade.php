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
                        @php 
                            $curso_atual = $cursosHabilitacoes[0]['codcur'];
                            $curso_anterior = $cursosHabilitacoes[0]['codcur'];
                        @endphp 
                        <optgroup label="{{ $curso_atual }}">
                        @foreach ($cursosHabilitacoes as $habilitacao)
                            @php 
                                $curso_anterior = $curso_atual;
                                $curso_atual = $habilitacao['codcur']; 
                            @endphp
                            @if ($curso_atual != $curso_anterior)
                                <!-- fecha optiongroup e começa outro -->
                                </optgroup>
                                <optgroup label="{{ $habilitacao['codcur'] }}">
                            @endif
                            <option value="{{ $habilitacao['codhab'] }}">{{ $habilitacao['codcur'] }} - {{ $habilitacao['codhab'] }} - {{ $habilitacao['nomhab'] }}</option>
                        @endforeach
                        </optgroup>
                    </select>
                </div> 
                <div class="form-group">
                    <label for="numcredisoptelt">Nº de créditos exigidos em displinas optativas eletivas</label>
                    <input class="form-control" id="numcredisoptelt" name="numcredisoptelt" pattern="\d*"  
                        placeholder="Nº de créditos exigidos em displinas optativas eletivas" type="text" required>
                </div>
                <div class="form-group">
                    <label for="numtotcredisoptelt">Nº de créditos totais exigidos em displinas optativas eletivas</label>
                    <input class="form-control" id="numtotcredisoptelt" name="numtotcredisoptelt" pattern="\d*"  
                        placeholder="Nº de créditos totais exigidos em displinas optativas eletivas" type="text" required>
                </div>                
                <div class="form-group">
                    <label for="numcredisoptliv">Nº de créditos exigidos em displinas optativas livres</label>
                    <input class="form-control" id="numcredisoptliv" name="numcredisoptliv"  pattern="\d*" 
                        placeholder="Nº de créditos exigidos em displinas optativas livres" type="text" required>
                </div>
                <div class="form-group">
                    <label for="numtotcredisoptliv">Nº de créditos totais exigidos em displinas optativas livres</label>
                    <input class="form-control" id="numtotcredisoptliv" name="numtotcredisoptliv"  pattern="\d*" 
                        placeholder="Nº de créditos totais exigidos em displinas optativas livres" type="text" required>
                </div>                
                <div class="form-group">
                    <label for="dtainicrl">Ano de ingresso</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="glyphicon glyphicon-calendar"></i>
                        </div>
                        <input id="dtainicrl" name="dtainicrl" class="form-control datepicker" 
                            data-inputmask="'alias': 'dd/mm/yyyy'" data-mask="" type="text" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Observações</label>
                        <textarea id="txtobs" name="txtobs" class="form-control" rows="3" placeholder="Digite aqui"></textarea>
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

        // Exemplo adaptado de https://stackoverflow.com/questions/43820002/filter-seelct2-by-optgroup-from-another-select2
        $(document).ready(function() {
            let habilitacao_html = $('select[name=codhab]').html();
            // Define os dois selects como select2
            $('[name=codcur]').select2({placeholder: 'Selecione um curso', width: '100%'});
            $('[name=codhab]').select2({placeholder: 'Selecione uma habilitação', width: '100%'});

            // Ao alterar o select do curso, atualiza a lista das habilitações
            $('select[name=codcur]').change(function() {
                let curso_selecionado = $('[name=codcur] :selected').val();
                let habilitacao = $('select[name=codhab]');

                // Restaura as opções iniciais para habilitação
                habilitacao.html(habilitacao_html);
                
                // Verifica qual curso foi selecionado por meio do option group
                let opt_group = $('optgroup[label="' + curso_selecionado + '"]')[0].outerHTML;
                // 'Filtra' o select da habilitação
                habilitacao.html(opt_group);
            });
        });
    </script>

@stop