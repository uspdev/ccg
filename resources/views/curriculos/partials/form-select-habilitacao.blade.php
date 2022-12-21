<div class="form-group">
  <label>Habilitação</label>
  <select class="form-control select2-habilitacao" style="width: 100%;" id="codhab" name="codhab" required>
    <option></option>
    @php
      $curso_atual = $cursosHabilitacoes[0]['codcur'];
      $curso_anterior = $cursosHabilitacoes[0]['codcur'];
    @endphp
    <optgroup label="{{ $curso_atual }}">
      @foreach ($cursosHabilitacoes as $habilitacao)
        @php
          $curso_anterior = $curso_atual;
          $curso_atual = $habilitacao['codcur'];
        @endphp
        @if ($curso_atual != $curso_anterior)
          <!-- fecha optiongroup e começa outro -->
    </optgroup>
    <optgroup label="{{ $habilitacao['codcur'] }}">
      @endif
      {{-- 
      @if (isset($curriculo['codhab']) && $habilitacao['codhab'] == $curriculo['codhab'] && $habilitacao['codcur'] == $curriculo['codcur'])
        <option value="{{ $habilitacao['codhab'] }}" selected>{{ $habilitacao['codcur'] }} -
          {{ $habilitacao['codhab'] }} - {{ $habilitacao['nomhab'] }}</option>
      @else
        <option value="{{ $habilitacao['codhab'] }}">{{ $habilitacao['codcur'] }} -
          {{ $habilitacao['codhab'] }} - {{ $habilitacao['nomhab'] }}</option> --}}
      {{-- @endif --}}

      <option value="{{ $habilitacao['codhab'] }}">{{ $habilitacao['codcur'] }} - {{ $habilitacao['codhab'] }} -
        {{ $habilitacao['nomhab'] }}</option>
      @endforeach
    </optgroup>
  </select>
</div>

@section('javascripts_bottom')
  @parent

  <script type="text/javascript">
    $(document).ready(function() {

      //Initialize Select2 Elements
      $('.select2-habilitacao').select2({
        placeholder: "Selecione",
        allowClear: true,
        placeholder: 'Selecione uma habilitação',
        width: '100%'
      });

      var habilitacao_html = $('select[name=codhab]').html();
      
      // Ao alterar o select do curso, atualiza a lista das habilitações
      var updateHabilitacao = function() {
        curso_selecionado = $('[name=codcur] :selected').val();
        habilitacao = $('select[name=codhab]');

        // Restaura as opções iniciais para habilitação
        habilitacao.html(habilitacao_html);

        // Verifica qual curso foi selecionado por meio do option group
        let opt_group = $('optgroup[label="' + curso_selecionado + '"]')[0].outerHTML;
        // 'Filtra' o select da habilitação
        habilitacao.html(opt_group);
      }

      var codcur = $('select[name=codcur]')
      if (codcur.val()) {
        updateHabilitacao()
      }

      codcur.change(function() {
        updateHabilitacao()
      })
    });
  </script>
@endsection
