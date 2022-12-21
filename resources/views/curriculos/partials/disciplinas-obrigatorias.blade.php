<table class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
    <thead>
      <tr>
        <th><label>Diciplinas Obrigatórias</label></th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th>Disciplinas</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($disciplinasObrigatorias as $disciplinasObrigatoria)
        <tr>
          <td style="width: 70%;">{{ $disciplinasObrigatoria['coddis'] }} -
            {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinasObrigatoria['coddis']) }}</td>
          <td style="width: 30%;">
            {{-- Se existe disciplina equivalente cadastrada, mostra as equivalentes --}}
            @if (App\Models\DisciplinasObrigatoriasEquivalente::where('id_dis_obr', $disciplinasObrigatoria->id)->get()->count() > 0)
              <button type="button" class="btn btn-info btn-xs" title="Disciplinas Obrigatórias Equivalentes"
                onclick="location.href='disciplinasObrEquivalentes/{{ $disciplinasObrigatoria->id }}';">
                <span class="far fa-eye"></span>
              </button>
            @endif
            <button style="float: left; margin-right: 3px; margin-top: 1px;" type="button"
              class="btn btn-success btn-xs" title="Adicionar Disciplinas Obrigatórias Equivalentes"
              onclick="location.href='disciplinasObrEquivalentes/create/{{ $disciplinasObrigatoria->id }}';">
              <i class="fas fa-plus"></i>
            </button>
            {{-- Se não existe disciplina equivalente cadastrada, pode apagar --}}
            @if (App\Models\DisciplinasObrigatoriasEquivalente::where('id_dis_obr', $disciplinasObrigatoria->id)->get()->count() == 0)
              <form style="float: left; margin-right: 3px;" role="form" method="POST"
                action="disciplinasObrigatorias/{{ $disciplinasObrigatoria->id }}">
                {{ csrf_field() }}
                {{ method_field('delete') }}
                <button type="submit" class="btn btn-danger btn-xs confirm" title="Apagar disciplina">
                  <i class="far fa-trash-alt"></i>
                </button>
              </form>
              {{-- Se não, exibe modal avisando --}}
            @else
              <button type="button" class="btn btn-danger btn-xs" title="Apagar disciplina" data-toggle="modal"
                data-target="#diciplinas{{ $disciplinasObrigatoria->id }}">
                <i class="far fa-trash-alt"></i>
              </button>
              {{-- Modais com as disciplinas equivalentes --}}
              <div class="modal modal-danger fade" id="diciplinas{{ $disciplinasObrigatoria->id }}">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title">Esta Disciplina possui Equivalentes cadastradas!</h4>
                    </div>
                    <div class="modal-body">
                      <p>Disciplina Obrigatória: <strong>
                          {{ $disciplinasObrigatoria->coddis }} -
                          {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinasObrigatoria->coddis) }}
                        </strong></p>
                      <p><strong>As Diciplinas abaixo serão automaticamente removidas junto com a Disciplina
                          Obrigatória</strong></p>
                      <p><strong>Equivalentes</strong>
                        @foreach (App\Models\DisciplinasObrigatoriasEquivalente::where('id_dis_obr', $disciplinasObrigatoria->id)->get() as $obrigatoriaEquivalente)
                          <br />{{ $obrigatoriaEquivalente['coddis'] }} -
                          {{ Uspdev\Replicado\Graduacao::nomeDisciplina($obrigatoriaEquivalente['coddis']) }}
                        @endforeach
                      </p>
                    </div>
                    <form role="form" method="POST"
                      action="disciplinasObrigatorias/{{ $disciplinasObrigatoria->id }}">
                      {{ csrf_field() }}
                      {{ method_field('delete') }}
                      <div class="modal-footer">
                        <button type="button" class="btn btn-outline pull-left"
                          data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-outline">Apagar</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
