@extends('adminlte::page')

@section('title', env('APP_NAME') . ' - Currículo - ' . $curriculo['id'])

@section('content_header')
<h1>Currículo - {{ $curriculo['id'] }}</h1>
@stop

@section('content')

    @include('flash')

    <div class="box box-primary">
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
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
                <tr>
                    <td colspan="2">
                        <div style="border: 1px solid #ccc; padding: 5px;" id="disciplinas">
                            <div class="box-body table-responsive no-padding">
                                <label>Disciplinas obrigatórias</label>
                                <table class="table table-hover table-striped">
                                    <tbody>
                                        <tr>
                                            <th colspan="2">
                                                <button type="button" class="btn btn-success btn-sm" title="Adicionar Disciplina Obrigatória" onclick="location.href='';">
                                                    <span class="glyphicon glyphicon-plus"></span> Adicionar Disciplina Obrigatória
                                                </button>                             
                                            </th>
                                        </tr>                                 
                                        <tr>
                                            <td>XXX9999 - John Doe</td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-xs" title="Disciplinas Obrigatórias Equivalentes">
                                                    <span class="glyphicon glyphicon-list-alt"></span>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-xs" title="Apagar disciplina">
                                                    <span class="glyphicon glyphicon-trash"></span>
                                                </button>                                        
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                    
                            <div class="box-body table-responsive no-padding">
                                <label>Disciplinas Optativas Eletivas</label>
                                <table class="table table-hover table-striped">
                                    <tbody>
                                        <tr>
                                            <th colspan="2">
                                                <button type="button" class="btn btn-success btn-sm" title="Adicionar Disciplina Obrigatória" onclick="location.href='';">
                                                    <span class="glyphicon glyphicon-plus"></span> Adicionar Disciplina Optativa Eletiva
                                                </button>                             
                                            </th>
                                        </tr>                                  
                                        <tr>
                                            <td>XXX9999 - John Doe</td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-xs" title="Disciplinas Obrigatórias Equivalentes">
                                                    <span class="glyphicon glyphicon-list-alt"></span>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-xs" title="Apagar disciplina">
                                                    <span class="glyphicon glyphicon-trash"></span>
                                                </button>                                        
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>  
                    
                            <div class="box-body table-responsive no-padding">
                                <label>Disciplinas Licenciaturas (Faculdade de Educação)</label>
                                <table class="table table-hover table-striped">
                                    <tbody>
                                        <tr>
                                            <th colspan="2">
                                                <button type="button" class="btn btn-success btn-sm" title="Adicionar Disciplina Obrigatória" onclick="location.href='';">
                                                    <span class="glyphicon glyphicon-plus"></span> Adicionar Disciplina Licenciaturas (Faculdade de Educação)
                                                </button>                             
                                            </th>
                                        </tr>                                  
                                        <tr>
                                            <td>XXX9999 - John Doe</td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-xs" title="Disciplinas Obrigatórias Equivalentes">
                                                    <span class="glyphicon glyphicon-list-alt"></span>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-xs" title="Apagar disciplina">
                                                    <span class="glyphicon glyphicon-trash"></span>
                                                </button>                                        
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> 
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

@stop
