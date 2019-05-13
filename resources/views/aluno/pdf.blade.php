<html>
    <head>
        <meta charset="UTF-8">
        <style>
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

        <footer>
            <strong>SERVIÇO DE GRADUAÇÃO</strong>
        </footer>

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
                    <td>&nbsp;</td>
                    <td align="center">Eletivas</td>
                    <td align="center">Livres</td>
                </tr>  
                <tr style="border-top: 1px #000 solid;">
                    <td style="border-top: 1px #000 solid;">Créditos-aula necessários</td>
                    <td align="center" style="border-top: 1px #000 solid;">{{ $curriculoAluno->numcredisoptelt }}</td>
                    <td align="center" style="border-top: 1px #000 solid;">{{ $curriculoAluno->numcredisoptliv }}</td>
                </tr> 
                <tr>
                    <td>Créditos-aula cursados</td>
                    <td align="center">{{ $numcredisoptelt }}</td>
                    <td align="center">{{ $numcredisoptliv }}</td>
                </tr>   
                <tr style="border-top: 1px #000 solid;">
                    <td style="border-top: 1px #000 solid;">Créditos-aula a concluir</td>
                    <td align="center" style="border-top: 1px #000 solid;">{{ $curriculoAluno->numcredisoptelt - $numcredisoptelt }}</td>
                    <td align="center" style="border-top: 1px #000 solid;">{{ $curriculoAluno->numcredisoptliv - $numcredisoptliv }}</td>
                </tr>                                                           																							
            </table>          
            <h3>Observações:</h3>
            <ul><li>Nada consta</li></ul>
            <p>Serviço de Graduação, {{ Carbon\Carbon::parse(now())->isoFormat('d \d\e MMMM \d\e Y') }}.</p> 
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
    
    </body>
</html>




