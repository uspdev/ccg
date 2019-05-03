

<strong>{{ $dadosAcademicos->codpes }} - {{ $dadosAcademicos->nompes }}</strong><br />
Curso: {{ $dadosAcademicos->codcur }} - {{ $dadosAcademicos->nomcur }}<br />
Habilitação: {{ $dadosAcademicos->codhab }} - {{ $dadosAcademicos->nomhab }}<br />
Ano de ingresso: {{ Carbon\Carbon::parse($dadosAcademicos->dtainivin)->format('Y') }}<br />
Programa: {{ $dadosAcademicos->codpgm }}

