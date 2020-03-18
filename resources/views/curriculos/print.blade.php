@extends('adminlte::page')

@section('title', config('app.name') . ' - Versão impressa do Currículo ' . $curriculo['id'])

@section('content_header')

<style>
    .main-header {display: none;}
    .main-sidebar {display: none;}
    .content-wrapper {margin-left: 0px;}
</style>

<h1>Currículo {{ $curriculo['id'] }}</h1>
@stop

@section('content')
    
    <div class="nav-tabs-custom">
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
                                <th>Nº de créditos totais exigidos em displinas optativas eletivas</td>
                                <td>{{ $curriculo['numtotcredisoptelt'] }}</td>
                            </tr>                             
                            <tr>
                                <th>Nº de créditos exigidos em displinas optativas livres</td>
                                <td>{{ $curriculo['numcredisoptliv'] }}</td>
                            </tr> 
                            <tr>
                                <th>Nº de créditos totais exigidos em displinas optativas livres</td>
                                <td>{{ $curriculo['numtotcredisoptliv'] }}</td>
                            </tr>                                                                     
                            <tr>
                                <th>Ano de ingresso</td>
                                <td>{{ Carbon\Carbon::parse($curriculo['dtainicrl'])->format('Y') }}</td>
                            </tr> 
                            <tr>
                                <th>Observações</td>
                                <td>{!! nl2br(e($curriculo['txtobs'])) !!}</td>
                            </tr>                             
                        </table>                       
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
            </div>
        </div>
    </div>

@stop
