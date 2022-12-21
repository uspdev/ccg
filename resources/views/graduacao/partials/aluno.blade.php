<div class="box-body table-responsive no-padding">
    <table class="table table-hover">
      <tr>
        <th>Curso</th>
        <td>{{ $dadosAcademicos->codcur }} - {{ $dadosAcademicos->nomcur }}</td>
      </tr>
      <tr>
        <th>Habilitação</td>
        <td>{{ $dadosAcademicos->codhab }} - {{ $dadosAcademicos->nomhab }}</td>
      </tr>
      <tr>
        <th>Ano de ingresso</td>
        <td>{{ Carbon\Carbon::parse($dadosAcademicos->dtainivin)->format('Y') }}</td>
      </tr>
      <tr>
        <th>Programa</td>
        <td>{{ $dadosAcademicos->codpgm }}</td>
      </tr>
      @if (isset($curriculoAluno->id_crl))
        <tr>
          <th colspan="2">
            <table class="table table-hover table-striped">
              <thead>
                <tr>
                  <th style="border-bottom: 1px solid #000;">&nbsp;</th>
                  <th style="border-bottom: 1px solid #000;">Necessários</th>
                  <th style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Cursados</th>
                  <th style="border-bottom: 1px solid #000;">A concluir</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Créditos-aula em eletivas</td>
                  <td>{{ $curriculoAluno->numcredisoptelt }}</td>
                  <td style="border-right: 1px solid #000;">{{ $numcredisoptelt }}</td>
                  <td>
                    {{ $curriculoAluno->numcredisoptelt - $numcredisoptelt < 0 ? 0 : $curriculoAluno->numcredisoptelt - $numcredisoptelt }}
                  </td>
                </tr>
                <tr>
                  <td>Créditos totais em eletivas</td>
                  <td>{{ $curriculoAluno->numtotcredisoptelt }}</td>
                  <td style="border-right: 1px solid #000;">{{ $numtotcredisoptelt }}</td>
                  <td>
                    {{ $curriculoAluno->numtotcredisoptelt - $numtotcredisoptelt < 0 ? 0 : $curriculoAluno->numtotcredisoptelt - $numtotcredisoptelt }}
                  </td>
                </tr>
                <tr>
                  <td>Créditos-aula em livres</td>
                  <td>{{ $curriculoAluno->numcredisoptliv }}</td>
                  <td style="border-right: 1px solid #000;">{{ $numcredisoptliv }}</td>
                  <td>
                    {{ $curriculoAluno->numcredisoptliv - $numcredisoptliv < 0 ? 0 : $curriculoAluno->numcredisoptliv - $numcredisoptliv }}
                  </td>
                </tr>
                <tr>
                  <td>Créditos totais em livres</td>
                  <td>{{ $curriculoAluno->numtotcredisoptliv }}</td>
                  <td style="border-right: 1px solid #000;">{{ $numtotcredisoptliv }}</td>
                  <td>
                    {{ $curriculoAluno->numtotcredisoptliv - $numtotcredisoptliv < 0 ? 0 : $curriculoAluno->numtotcredisoptliv - $numtotcredisoptliv }}
                  </td>
                </tr>
              </tbody>
            </table>
            </td>
        </tr>
      @endif
    </table>
  </div>