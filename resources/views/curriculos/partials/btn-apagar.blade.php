{{-- Se não existe disciplina cadastrada, pode apagar --}}
<form role="form" method="POST" action="curriculos/{{ $curriculo->id }}" style="display:inline-block">
  {{ csrf_field() }}
  {{ method_field('delete') }}

  @if (App\Models\DisciplinasObrigatoria::where('id_crl', $curriculo->id)->get()->count() ==
      0 and
      App\Models\DisciplinasOptativasEletiva::where('id_crl', $curriculo->id)->get()->count() ==
          0 and
      App\Models\DisciplinasLicenciatura::where('id_crl', $curriculo->id)->get()->count() ==
          0)
    <button type="submit" class="btn btn-danger btn-xs confirm" title="Apagar Currículo">
      <span class="far fa-trash-alt"></span>
    </button>
  @else
    @include('curriculos.partials.btn-apagar-modal')
  @endif
</form>
