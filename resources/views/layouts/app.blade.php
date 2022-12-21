@extends('laravel-usp-theme::master')

@section('title')
  @parent
@endsection

@section('styles')
  @parent
  <style>
    /*seus estilos*/
  </style>
@endsection

@section('javascripts_bottom')
  @parent
  <script>

    // botão de confirmação para delete
    $(document).ready(function() {
      $('.confirm').on('click', function(e) {
        if (confirm('Tem certeza?') != true) {
          e.preventDefault();
        }
      })
    })
  </script>
@endsection
