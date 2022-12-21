<table class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
  <thead>
    <tr>
      <th><label>Diciplinas Licenciaturas (Faculdade de Educação)</label></th>
      <th>&nbsp;</th>
    </tr>
    <tr>
      <th>Disciplinas</th>
      <th>Ações</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($disciplinasLicenciaturas as $disciplinasLicenciatura)
      <tr>
        <td style="width: 70%;">{{ $disciplinasLicenciatura['coddis'] }} -
          {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinasLicenciatura['coddis']) }}</td>
        <td style="width: 30%;">
          {{-- Se existe disciplina equivalente cadastrada, mostra as equivalentes --}}
          @if (App\Models\DisciplinasLicenciaturasEquivalente::where('id_dis_lic', $disciplinasLicenciatura->id)->get()->count() > 0)
            <button type="button" class="btn btn-info btn-xs" title="Disciplinas Licenciaturas Equivalentes"
              onclick="location.href='disciplinasLicEquivalentes/{{ $disciplinasLicenciatura->id }}';">
              <span class="far fa-eye"></span>
            </button>
          @endif
          <button style="float: left; margin-right: 3px; margin-top: 1px;" type="button" class="btn btn-success btn-xs"
            title="Adicionar Disciplinas Licenciaturas Equivalentes"
            onclick="location.href='disciplinasLicEquivalentes/create/{{ $disciplinasLicenciatura->id }}';">
            <i class="fas fa-plus"></i>
          </button>
          {{-- Se não existe disciplina equivalente cadastrada, pode apagar --}}
          @if (App\Models\DisciplinasLicenciaturasEquivalente::where('id_dis_lic', $disciplinasLicenciatura->id)->get()->count() == 0)
            <form style="float: left; margin-right: 3px;" role="form" method="POST"
              action="disciplinasLicenciaturas/{{ $disciplinasLicenciatura->id }}">
              {{ csrf_field() }}
              {{ method_field('delete') }}
              <button type="submit" class="btn btn-danger btn-xs confirm" title="Apagar disciplina">
                <i class="far fa-trash-alt"></i>
              </button>
            </form>
            {{-- Se não, exibe modal avisando --}}
          @else
            <button type="button" class="btn btn-danger btn-xs" title="Apagar disciplina" data-toggle="modal"
              data-target="#diciplinas{{ $disciplinasLicenciatura->id }}">
              <i class="far fa-trash-alt"></i>
            </button>
            {{-- Modais com as disciplinas equivalentes --}}
            <div class="modal modal-danger fade" id="diciplinas{{ $disciplinasLicenciatura->id }}">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Esta Disciplina possui Equivalentes cadastradas!</h4>
                  </div>
                  <div class="modal-body">
                    <p>Disciplina Licenciatura: <strong>
                        {{ $disciplinasLicenciatura->coddis }} -
                        {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinasLicenciatura->coddis) }}
                      </strong></p>
                    <p><strong>As Diciplinas abaixo serão automaticamente removidas junto com a Disciplina
                        Licenciatura</strong></p>
                    <p><strong>Equivalentes</strong>
                      @foreach (App\Models\DisciplinasLicenciaturasEquivalente::where('id_dis_lic', $disciplinasLicenciatura->id)->get() as $licenciaturaEquivalente)
                        <br />{{ $licenciaturaEquivalente['coddis'] }} -
                        {{ Uspdev\Replicado\Graduacao::nomeDisciplina($licenciaturaEquivalente['coddis']) }}
                      @endforeach
                    </p>
                  </div>
                  <form role="form" method="POST"
                    action="disciplinasLicenciaturas/{{ $disciplinasLicenciatura->id }}">
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
          @endif
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
