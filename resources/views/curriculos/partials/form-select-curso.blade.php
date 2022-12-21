<div class="form-group">
  <label>Curso</label>
  <select class="form-control select2-curso" style="width: 100%;" id="codcur" name="codcur" required>
    <option></option>
    @foreach ($cursos as $curso)
      @if (isset($curriculo['codcur']) && $curso['codcur'] == $curriculo['codcur'])
        <option value="{{ $curso['codcur'] }}" selected>{{ $curso['codcur'] }} - {{ $curso['nomcur'] }}</option>
      @else
        <option value="{{ $curso['codcur'] }}">{{ $curso['codcur'] }} - {{ $curso['nomcur'] }}</option>
      @endif
    @endforeach
  </select>
</div>

@section('javascripts_bottom')
  @parent

  <script type="text/javascript">
    $(document).ready(function() {

      //Initialize Select2 Elements
      $('.select2-curso').select2({
        placeholder: "Selecione",
        allowClear: true,
        placeholder: 'Selecione um curso',
        width: '100%'
      });

    });
  </script>
@endsection
