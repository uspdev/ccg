<table class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
    <thead>
      <tr>
        <th><label>Diciplinas Optativas Eletivas</label></th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th>Disciplinas</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($disciplinasOptativasEletivas as $disciplinasOptativasEletiva)
        <tr>
          <td style="width: 70%;">{{ $disciplinasOptativasEletiva['coddis'] }} -
            {{ Uspdev\Replicado\Graduacao::nomeDisciplina($disciplinasOptativasEletiva['coddis']) }}</td>
          <td style="width: 30%;">
            <form role="form" method="POST"
              action="disciplinasOptativasEletivas/{{ $disciplinasOptativasEletiva->id }}">
              {{ csrf_field() }}
              {{ method_field('delete') }}
              <button type="submit" class="btn btn-danger btn-xs confirm" title="Apagar disciplina">
                <i class="far fa-trash-alt"></i>
              </button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
