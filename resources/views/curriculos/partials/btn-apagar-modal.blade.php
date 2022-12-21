<button type="button" class="btn btn-danger btn-xs" title="Apagar Currículo" data-toggle="modal"
  data-target="#diciplinas{{ $curriculo->id }}">
  <i class="far fa-trash-alt"></i>
</button>

{{-- Modais com as disciplinas do currículo --}}
<div class="modal modal-danger fade" id="diciplinas{{ $curriculo->id }}">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Este currículo possui Disciplinas cadastradas!</h4>
      </div>
      <div class="modal-body">
        <p>
          Curso: {{ $curriculo->codcur }} -
          {{ Uspdev\Replicado\Graduacao::nomeCurso($curriculo->codcur) }}<br />
          Habilitação: {{ $curriculo->codhab }} -
          {{ Uspdev\Replicado\Graduacao::nomeHabilitacao($curriculo->codhab, $curriculo->codcur) }}<br />
          Ingresso: {{ Carbon\Carbon::parse($curriculo->dtainicrl)->format('Y') }}
        </p>
        <p><strong>As Diciplinas abaixo serão automaticamente removidas junto com o Currículo</strong>
        </p>
        <p><strong>Obrigatórias</strong>
          @foreach (App\Models\DisciplinasObrigatoria::where('id_crl', $curriculo->id)->get() as $obrigatoria)
            <br />{{ $obrigatoria['coddis'] }} -
            {{ Uspdev\Replicado\Graduacao::nomeDisciplina($obrigatoria['coddis']) }}
          @endforeach
        </p>
        <p><strong>Optativas Eletivas</strong>
          @foreach (App\Models\DisciplinasOptativasEletiva::where('id_crl', $curriculo->id)->get() as $optativasEletiva)
            <br />{{ $optativasEletiva['coddis'] }} -
            {{ Uspdev\Replicado\Graduacao::nomeDisciplina($optativasEletiva['coddis']) }}
          @endforeach
        </p>
        <p><strong>Licenciaturas</strong>
          @foreach (App\Models\DisciplinasLicenciatura::where('id_crl', $curriculo->id)->get() as $licenciatura)
            <br />{{ $licenciatura['coddis'] }} -
            {{ Uspdev\Replicado\Graduacao::nomeDisciplina($licenciatura['coddis']) }}
          @endforeach
        </p>
      </div>
      <form role="form" method="POST" action="curriculos/{{ $curriculo->id }}">
        {{ csrf_field() }}
        {{ method_field('delete') }}
        <div class="modal-footer">
          <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-outline">Apagar</button>
        </div>
      </form>
    </div>
  </div>
</div>
