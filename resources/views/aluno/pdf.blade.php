@can('secretaria')
<html>
    <head>
        <meta charset="UTF-8">
        <style type="text/css" media="print">
            /** 
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
            @page {
                margin: 2cm 2cm;
            }

            /** Define now the real margins of every page in the PDF **/
            body {
                position: fixed;
                top: 3cm;
            }

            /** Define the header rules **/
            header {
                position: fixed;
                top: 0cm;
                left: 0.2cm;
                right: 0cm;
                height: 2cm;

                /** Extra personal styles **/
                color: #13438D;
                line-height: 1.5cm;
            }

            /** Define the footer rules **/
            footer {
                position: fixed; 
                top: 2cm;
                bottom: 0cm; 
                left: 0.2cm; 
                right: 0cm;
                height: 2cm;

                /** Extra personal styles **/
                color: #13438D;
                line-height: 1.5cm;
            }
        </style>
    </head>
    <body>
        
        <!-- Define header and footer blocks before your content -->
        <header>
            <img src="{{ public_path('/images/logoUnd.png') }}" width="300px">
        </header>

        <!-- Wrap the content of your PDF inside a main tag -->
        <main>

            <h2>{{ $dadosAcademicos->codpes }} - {{ $dadosAcademicos->nompes }}</h2>
            <table width="100%">
                <tr>
                    <td width="15%">Curso:</td>
                    <td width="75%">{{ $dadosAcademicos->codcur }} - {{ $dadosAcademicos->nomcur }}</td>
                </tr>
                <tr>
                    <td>Habilitação:</td>
                    <td>{{ $dadosAcademicos->codhab }} - {{ $dadosAcademicos->nomhab }}</td>
                </tr>  
                <tr>
                    <td>Ano de ingresso:</td>
                    <td>{{ Carbon\Carbon::parse($dadosAcademicos->dtainivin)->format('Y') }}</td>
                </tr> 
                <tr>
                    <td>Programa:</td>
                    <td>{{ $dadosAcademicos->codpgm }}</td>
                </tr>                                               
            </table>  
            <h1 align="center">Análise de Currículo</h1>
            <h3>Disciplinas obrigatórias a concluir:</h3>
            <ul>
            @forelse ($disciplinasObrigatoriasFaltam as $disciplinaObrigatoriaFalta)                  
                <li>{{ $disciplinaObrigatoriaFalta }} - 
                    {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinaObrigatoriaFalta) }}
                    @foreach ($disciplinasObrigatoriasEquivalentesFaltam as $key => $value)
                        @if ($key == $disciplinaObrigatoriaFalta)  
                            @foreach ($value as $k => $v)
                                <br />&nbsp;&nbsp;&nbsp;&nbsp;
                                <strong>{{ $v[1] }}</strong> {{ $v[0] }} 
                                {{ Uspdev\Replicado\Graduacao::nomeDisciplina($v[0]) }}
                            @endforeach 
                        @endif 
                    @endforeach
                </li>                 
            @empty
                <li>Nada consta</li>
            @endforelse
            </ul>
            <h3>Disciplinas licenciaturas a concluir:</h3>
            <ul>
            @forelse ($disciplinasLicenciaturasFaltam as $disciplinaLicenciaturaFalta)                  
			    <li>{{ $disciplinaLicenciaturaFalta }} - 
                    {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinaLicenciaturaFalta) }}
                    @foreach ($disciplinasLicenciaturasEquivalentesFaltam as $key => $value)
                        @if ($key == $disciplinaLicenciaturaFalta)  
                            @foreach ($value as $k => $v)
                                <br />&nbsp;&nbsp;&nbsp;&nbsp;
                                <strong>{{ $v[1] }}</strong> {{ $v[0] }} 
                                {{ Uspdev\Replicado\Graduacao::nomeDisciplina($v[0]) }}
                            @endforeach 
                        @endif 
                    @endforeach                    
				</li>
            @empty
                <li>Nada consta</li>
            @endforelse        
            </ul>
            <h3>Créditos em disciplinas optativas:</h3> 
            <table width="60%">
                <tr>
                    <td style="text-align: center; border-bottom: 1px solid #000;">&nbsp;</td>
                    <td style="text-align: center; border-bottom: 1px solid #000;">Necessários</td>
                    <td style="text-align: center; border-bottom: 1px solid #000; border-right: 1px solid #000;">Cursados</td>
                    <td style="text-align: center; border-bottom: 1px solid #000;">A concluir</td>												
                </tr>
                <tr>
                    <td>Créditos-aula em eletivas</td>
                    <td style="text-align: center;">{{ $curriculoAluno->numcredisoptelt }}</td>
                    <td style="text-align: center; border-right: 1px solid #000;">{{ $numcredisoptelt }}</td>
                    <td style="text-align: center;">{{ (($curriculoAluno->numcredisoptelt - $numcredisoptelt) < 0) ? 0 : $curriculoAluno->numcredisoptelt - $numcredisoptelt }}</td>
                </tr>	
                <tr>
                    <td>Créditos totais em eletivas</td>
                    <td style="text-align: center;">0</td>
                    <td style="text-align: center; border-right: 1px solid #000;">0</td>
                    <td style="text-align: center;">0</td>
                </tr>											
                <tr>
                    <td>Créditos-aula em livres</td>
                    <td style="text-align: center;">{{ $curriculoAluno->numcredisoptliv }}</td>
                    <td style="text-align: center; border-right: 1px solid #000;">{{ $numcredisoptliv }}</td>
                    <td style="text-align: center;">{{ (($curriculoAluno->numcredisoptliv - $numcredisoptliv) < 0) ? 0 : $curriculoAluno->numcredisoptliv - $numcredisoptliv }}</td>
                </tr>
                <tr>
                    <td>Créditos totais em livres</td>
                    <td style="text-align: center;">0</td>
                    <td style="text-align: center; border-right: 1px solid #000;">0</td>
                    <td style="text-align: center;">0</td>
                </tr>																																													
            </table>           
            <h3>Observações:</h3>
            <ul>
            @if ($curriculoAluno->txtobs != '')
                <li><p>
                    {!! nl2br(e($curriculoAluno->txtobs)) !!}
                </p></li>
            @endif                
            @if (isset(App\AlunosObservacoes::where(['id_crl' => $curriculoAluno->id_crl, 'codpes' => $dadosAcademicos->codpes])->first()->txtobs))
                <li><p>
                    {!! nl2br(e(App\AlunosObservacoes::where(['id_crl' => $curriculoAluno->id_crl, 'codpes' => $dadosAcademicos->codpes])->first()->txtobs)) !!}
                </p></li>
            @else
                <li>Nada consta</li>
            @endif
            </ul>
            <p>Serviço de Graduação, {{ Carbon\Carbon::parse(now())->isoFormat('D \d\e MMMM \d\e Y') }}.</p> 
            <table width="100%">                 
                <tr>
                    <td width="50%" align="center">&nbsp;</td>
                    <td width="50%" align="center">Ciente:</td>
                </tr> 
                <tr>
                    <td width="50%" align="center">&nbsp;</td>
                    <td width="50%" align="center">&nbsp;</td>
                </tr>  
                <tr>
                    <td width="50%" align="center">&nbsp;</td>
                    <td width="50%" align="center">&nbsp;</td>
                </tr>                                              
                <tr>
                    <td width="50%" align="center">______________________________</td>
                    <td width="50%" align="center">______________________________</td>
                </tr>                
                <tr>
                    <td width="50%" align="center">Responsável pela análise</td>
                    <td width="50%" align="center">{{ $dadosAcademicos->nompes }}</td>
                </tr>
            </table>    
        </main>

        <!-- <footer>
            <strong>SERVIÇO DE GRADUAÇÃO</strong>
        </footer> -->
    
    </body>
</html>
@endcan




